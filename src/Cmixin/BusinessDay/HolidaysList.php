<?php

namespace Cmixin\BusinessDay;

use DateTime;

class HolidaysList extends MixinBase
{
    /**
     * @var array
     */
    public $holidays = array();

    /**
     * @var string|null
     */
    public $holidaysRegion = null;

    /**
     * Set the holidays region (see src/Cmixin/Holidays for examples).
     *
     * @return \Closure
     */
    public function standardizeHolidaysRegion()
    {
        return function ($region) {
            $region = preg_replace('/[^a-z0-9_-]/', '', str_replace('_', '-', strtolower($region)));

            return strpos($region, '-') === false ? "$region-national" : $region;
        };
    }

    /**
     * Set the holidays region (see src/Cmixin/Holidays for examples).
     *
     * @return \Closure
     */
    public function setHolidaysRegion()
    {
        $mixin = $this;

        return function ($region) use ($mixin) {
            $region = call_user_func($mixin->standardizeHolidaysRegion(), $region);
            $mixin->holidaysRegion = $region;

            if (!isset($mixin->holidays[$region])) {
                if (!file_exists($file = __DIR__."/../Holidays/$region.php")) {
                    list($country, $subRegion) = array_pad(explode('-', $region, 2), 2, '');
                    $nation = "$country-national";

                    if (!isset($mixin->holidays[$nation])) {
                        if (!file_exists($file = __DIR__."/../Holidays/$nation.php")) {
                            return;
                        }

                        $mixin->holidays[$nation] = include $file;
                    }

                    if (isset($mixin->holidays[$nation]['regions'], $mixin->holidays[$nation]['regions'][$subRegion])) {
                        $newRegion = $mixin->holidays[$nation]['regions'][$subRegion];
                        $region = "$country-$newRegion";
                        $mixin->holidaysRegion = $region;
                        $mixin->holidays[$region] = include __DIR__."/../Holidays/$region.php";

                        return;
                    }

                    $mixin->holidaysRegion = $nation;
                    $file = __DIR__."/../Holidays/$nation.php";
                }

                $mixin->holidays[$region] = include $file;
            }
        };
    }

    /**
     * Get the holidays for the current region selected.
     *
     * @return \Closure
     */
    public function getHolidays()
    {
        $mixin = $this;

        return function ($region = null) use ($mixin) {
            $region = is_string($region)
                ? call_user_func($mixin->standardizeHolidaysRegion(), $region)
                : $mixin->holidaysRegion;

            if (!$region || !isset($mixin->holidays[$region])) {
                return array();
            }

            return $mixin->holidays[$region];
        };
    }

    /**
     * Set the holidays list.
     *
     * @return \Closure
     */
    public function setHolidays()
    {
        $mixin = $this;

        return function ($region, $holidays) use ($mixin) {
            $region = call_user_func($mixin->standardizeHolidaysRegion(), $region);
            $addHolidays = $mixin->addHolidays();
            $mixin->holidays[$region] = array();
            $addHolidays($region, $holidays);
        };
    }

    /**
     * Set the holidays list.
     *
     * @return \Closure
     */
    public function resetHolidays()
    {
        $mixin = $this;

        return function () use ($mixin) {
            $mixin->holidaysRegion = null;
            $mixin->holidays = array();
        };
    }

    /**
     * Get the holidays of the given year (current year if no parameter given).
     *
     * @return \Closure
     */
    public function getYearHolidays()
    {
        $getYearHolidaysNextFunction = static::getYearHolidaysNextFunction();

        return function ($year = null, $type = null, $self = null) use ($getYearHolidaysNextFunction) {
            $next = $getYearHolidaysNextFunction($year, $type, $self);
            $holidays = array();

            while ($data = $next()) {
                list($key, $holiday) = $data;

                $holidays[$key] = $holiday;
            }

            return $holidays;
        };
    }

    /**
     * Get the holidays of the given year (current year if no parameter given).
     *
     * @return \Closure
     */
    public function getYearHolidaysNextFunction()
    {
        $mixin = $this;
        $carbonClass = static::getCarbonClass();
        $getThisOrToday = static::getThisOrToday();

        return function ($year = null, $type = null, $self = null) use ($mixin, $carbonClass, $getThisOrToday) {
            $year = $year ?: $getThisOrToday($self, isset($this) && $this !== $mixin ? $this : null)->year;
            $holidays = $carbonClass::getHolidays();
            $outputClass = $type ? (is_string($type) && $type !== 'string' ? $type : 'DateTime') : $carbonClass;
            $holidaysList = array();

            return function () use ($year, $outputClass, $type, &$holidays, &$holidaysList) {
                while (true) {
                    while (($key = key($holidays)) === 'regions') {
                        next($holidays);
                    }

                    $holiday = current($holidays);

                    if (!$key && !$holiday) {
                        return false;
                    }

                    next($holidays);

                    if (is_callable($holiday)) {
                        $holiday = call_user_func($holiday, $year);
                    }

                    if (is_string($holiday)) {
                        if (substr($holiday, 0, 1) === '=') {
                            if ($substitute = preg_match('/\ssubstitute$/i', $holiday)) {
                                $holiday = trim(substr($holiday, 0, -11));
                            }

                            $holiday = preg_replace_callback('/(easter)/i', function ($match) use ($year) {
                                switch ($match[0]) {
                                    case 'easter':
                                        static $easterDays = array();

                                        if (!isset($easterDays[$year])) {
                                            $easterDays[$year] = easter_days($year);
                                        }

                                        return "$year-03-21 $easterDays[$year] days ";
                                }
                            }, trim(substr($holiday, 1)));
                            $holiday = preg_replace('/^\d{2}-\d{2}(\s[\s\S]*)?$/', "$year-$0", $holiday);
                            $holiday = str_replace('$year', $year, $holiday);
                            $holiday = preg_replace('/(\s\d+)\s*$/', '$1 days', $holiday);
                            list($holiday, $notOn) = array_pad(explode(' not on ', $holiday, 2), 2, null);

                            list($holiday, $condition) = array_pad(explode(' if ', $holiday, 2), 2, null);

                            if (strpos($holiday, "$year") === false) {
                                $holiday .= " $year";
                            }

                            try {
                                /** @var DateTime $dateTime */
                                $dateTime = new $outputClass($holiday);
                            } catch (\Exception $exception) {
                                continue;
                            }

                            if ($notOn) {
                                $notOn = strtolower($notOn);
                                $notOn = $notOn === 'weekend' ? 'Saturday, Sunday' : $notOn;

                                if (in_array(strtolower($dateTime->format('l')), array_map('trim', explode(',', $notOn)))) {
                                    continue;
                                }
                            }

                            if ($condition) {
                                $expected = true;

                                if (substr($condition, 0, 4) === 'not ') {
                                    $expected = false;
                                    $condition = substr($condition, 4);
                                }

                                list($condition, $action) = array_pad(explode(' then ', $condition, 2), 2, null);
                                $condition = strtolower($condition);
                                $condition = (bool) ($condition === 'weekend'
                                    ? ($dateTime->format('N') > 5)
                                    : in_array(strtolower($dateTime->format('l')), array_map('trim', explode(',', $condition)))
                                );

                                if ($condition === $expected) {
                                    $dateTime->modify($action);
                                }
                            }

                            while ($substitute && ($dateTime->format('N') > 5 || isset($holidaysList[$dateTime->format('d/m')]))) {
                                $dateTime->modify('+1 day');
                            }

                            $holiday = $dateTime->format('d/m');
                        }

                        if (strpos($holiday, '-') !== false) {
                            $holiday = preg_replace('/^(\d+)-(\d+)$/', '$2/$1', $holiday);
                            $holiday = preg_replace('/^(\d+)-(\d+)-(\d+)$/', '$3/$2/$1', $holiday);
                        }

                        $holidaysList[$holiday] = true;
                    }

                    try {
                        return array($key, $holiday ? ($type === 'string' ? $holiday : (isset($dateTime) ? $dateTime : $outputClass::createFromFormat('d/m/Y', "$holiday/$year"))) : $holiday);
                    } catch (\InvalidArgumentException $exception) {
                        continue;
                    }
                }
            };
        };
    }

    /**
     * Initialize holidays region storage.
     *
     * @param string|null $region
     *
     * @return \Closure|$this
     */
    public function initializeHolidaysRegion($region = null)
    {
        if ($region) {
            $region = call_user_func($this->standardizeHolidaysRegion(), $region);

            if (!isset($this->holidays[$region])) {
                $this->holidays[$region] = array();
            }

            return $this;
        }

        return function () {
            return true;
        };
    }

    /**
     * Push a holiday to the holidays list of a region.
     *
     * @return \Closure
     */
    public function pushHoliday()
    {
        $mixin = $this;

        return function ($region, $holiday, $key = null) use ($mixin) {
            $region = call_user_func($mixin->standardizeHolidaysRegion(), $region);
            $mixin->initializeHolidaysRegion($region);

            if (is_string($key)) {
                $mixin->holidays[$region][$key] = $holiday;

                return isset($this) && $this !== $mixin ? $this : null;
            }

            $mixin->holidays[$region][] = $holiday;

            return isset($this) && $this !== $mixin ? $this : null;
        };
    }

    /**
     * Set the name(s) of a holiday.
     *
     * @return \Closure
     */
    public function setHolidayName()
    {
        $mixin = $this;

        return function ($holidayKey = null, $name = null, $value = null) use ($mixin) {
            static $dictionary;

            if ($mixin instanceof Holiday && ($name = is_string($name) ? array($name => $value) : $name)) {
                if (!isset($dictionary)) {
                    $dictionary = $mixin->getHolidayNamesDictionary();
                }
                foreach ($name as $language => $text) {
                    $dictionary($language);
                    $mixin->holidayNames[$language][$holidayKey] = $text;
                }
            }

            return isset($this) && $this !== $mixin ? $this : null;
        };
    }

    /**
     * Add a holiday to the holidays list of a region then init name and observed state.
     *
     * @return \Closure
     */
    public function addHoliday()
    {
        $mixin = $this;
        $dictionary = $this->setHolidayName();

        return function ($region, $holiday, $key = null, $name = null, $observed = null) use ($mixin, $dictionary) {
            static $observer;

            $region = call_user_func($mixin->standardizeHolidaysRegion(), $region);
            $mixin->initializeHolidaysRegion($region);
            $push = $mixin->pushHoliday();
            $push($region, $holiday, $key);

            $dictionary($key, $name);

            if (isset($observed) && $mixin instanceof HolidayObserver) {
                if (!isset($observer)) {
                    $observer = $mixin->setHolidayObserveStatus();
                }
                $observer($key, $observed);
            }

            return isset($this) && $this !== $mixin ? $this : null;
        };
    }

    /**
     * Unpack a holiday array definition.
     *
     * @return \Closure
     */
    public function unpackHoliday()
    {
        return function (&$holiday, &$name = null, &$observed = null) {
            if (!isset($holiday['date'])) {
                throw new \InvalidArgumentException(
                    'Holiday array definition should at least contains a "date" entry.'
                );
            }

            if (isset($holiday['name'])) {
                $name = $holiday['name'];
            }

            if (isset($holiday['observed'])) {
                $observed = $holiday['observed'];
            }

            $holiday = $holiday['date'];

            return $holiday;
        };
    }

    /**
     * Check a holiday definition and unpack it if it's an array.
     *
     * @return \Closure
     */
    public function checkHoliday()
    {
        $mixin = $this;

        return function (&$holiday, $key, &$name = null, &$observed = null) use ($mixin) {
            $unpack = $mixin->unpackHoliday();

            if (is_array($holiday)) {
                if (is_int($key)) {
                    throw new \InvalidArgumentException(
                        'Holiday array definition need a string identifier as main array key.'
                    );
                }

                $unpack($holiday, $name, $observed);
            }

            return $holiday;
        };
    }

    /**
     * Add holidays to the holidays list.
     *
     * @return \Closure
     */
    public function addHolidays()
    {
        $mixin = $this;

        return function ($region, $holidays) use ($mixin) {
            $region = call_user_func($mixin->standardizeHolidaysRegion(), $region);
            $mixin->initializeHolidaysRegion($region);
            $add = $mixin->addHoliday();
            $check = $mixin->checkHoliday();

            foreach ($holidays as $key => $holiday) {
                $name = null;
                $observed = null;
                $check($holiday, $key, $name, $observed);
                $add($region, $holiday, $key, $name, $observed);
            }
        };
    }
}
