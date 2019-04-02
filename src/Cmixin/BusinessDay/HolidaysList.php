<?php

namespace Cmixin\BusinessDay;

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
     * Return a standardized region name.
     *
     * @return \Closure
     */
    public function standardizeHolidaysRegion()
    {
        /**
         * Return a standardized region name.
         *
         * @param string $region
         *
         * @return string
         */
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

        /**
         * Set the holidays region (see src/Cmixin/Holidays for examples).
         *
         * @param string $region
         */
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
     * Get the current holidays region.
     *
     * @return \Closure
     */
    public function getHolidaysRegion()
    {
        $mixin = $this;

        /**
         * Get the current holidays region.
         *
         * @return null|string
         */
        return function () use ($mixin) {
            return $mixin->holidaysRegion;
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

        /**
         * Get the holidays for the current region selected.
         *
         * @param string $region
         *
         * @return array
         */
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

        /**
         * Set the holidays list.
         *
         * @param string $region
         * @param array  $holidays
         */
        return function ($region, $holidays) use ($mixin) {
            $region = call_user_func($mixin->standardizeHolidaysRegion(), $region);
            $addHolidays = $mixin->addHolidays();
            $mixin->holidays[$region] = array();
            $addHolidays($region, $holidays);
        };
    }

    /**
     * Reset the holidays list.
     *
     * @return \Closure
     */
    public function resetHolidays()
    {
        $mixin = $this;

        /**
         * Reset the holidays list.
         */
        return function () use ($mixin) {
            $mixin->holidaysRegion = null;
            $mixin->holidays = array();
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

        /**
         * Push a holiday to the holidays list of a region.
         *
         * @param string          $region    region where the holiday is observed.
         * @param string|\Closure $holiday   date or closure that get the year as parameter and returns the date
         * @param string          $holidayId optional holiday ID
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface|null
         */
        return function ($region, $holiday, $holidayId = null) use ($mixin) {
            $region = call_user_func($mixin->standardizeHolidaysRegion(), $region);
            $mixin->initializeHolidaysRegion($region);

            if (is_string($holidayId)) {
                $mixin->holidays[$region][$holidayId] = $holiday;

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

        /**
         * Set/change the name of holiday by ID for a given language (or a list of languages).
         *
         * @param string       $holidayKey holiday ID (identifier key)
         * @param string|array $language   language 2-chars code (or an array with languages codes as keys and new names for each language as value).
         * @param string       $name       new name (ignored if $language is an array)
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface|null
         */
        return function ($holidayKey = null, $language = null, $name = null) use ($mixin) {
            static $dictionary;

            if ($mixin instanceof Holiday && ($language = is_string($language) ? array($language => $name) : $language)) {
                if (!isset($dictionary)) {
                    $dictionary = $mixin->getHolidayNamesDictionary();
                }

                foreach ($language as $languageId => $name) {
                    $dictionary($languageId);
                    $mixin->holidayNames[$languageId][$holidayKey] = $name;
                }
            }

            return isset($this) && $this !== $mixin ? $this : null;
        };
    }

    /**
     * Add a holiday to the holidays list of a region and optionally init its ID, name and observed state.
     *
     * @return \Closure
     */
    public function addHoliday()
    {
        $mixin = $this;
        $dictionary = $this->setHolidayName();

        /**
         * Add a holiday to the holidays list of a region and optionally init its ID, name and observed state.
         *
         * @param string          $region    region where the holiday is observed.
         * @param string|\Closure $holiday   date or closure that get the year as parameter and returns the date
         * @param string          $holidayId optional holiday ID
         * @param string          $name      optional name
         * @param bool            $observed  optional observed state
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface|null
         */
        return function ($region, $holiday, $holidayId = null, $name = null, $observed = null) use ($mixin, $dictionary) {
            static $observer;

            $region = call_user_func($mixin->standardizeHolidaysRegion(), $region);
            $mixin->initializeHolidaysRegion($region);
            $push = $mixin->pushHoliday();
            $push($region, $holiday, $holidayId);

            $dictionary($holidayId, $name);

            if (isset($observed) && $mixin instanceof HolidayObserver) {
                if (!isset($observer)) {
                    $observer = $mixin->setHolidayObserveStatus();
                }

                $observer($holidayId, $observed);
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
        /**
         * Unpack a holiday array definition.
         *
         * @param array  &$holiday
         * @param string &$name
         * @param bool   &$observed
         *
         * @return array
         */
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

        /**
         * Check a holiday definition and unpack it if it's an array.
         *
         * @param array  &$holiday
         * @param string $holidayId
         * @param string &$name
         * @param bool   &$observed
         *
         * @return array
         */
        return function (&$holiday, $holidayId, &$name = null, &$observed = null) use ($mixin) {
            $unpack = $mixin->unpackHoliday();

            if (is_array($holiday)) {
                if (is_int($holidayId)) {
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

        /**
         * Add a holiday to the holidays list of a region and optionally init their IDs, names and observed states (if provided as array-definitions).
         *
         * @param string $region
         * @param array  $holidays
         */
        return function ($region, $holidays) use ($mixin) {
            $region = call_user_func($mixin->standardizeHolidaysRegion(), $region);
            $mixin->initializeHolidaysRegion($region);
            $add = $mixin->addHoliday();
            $check = $mixin->checkHoliday();

            foreach ($holidays as $holidayId => $holiday) {
                $name = null;
                $observed = null;
                $check($holiday, $holidayId, $name, $observed);
                $add($region, $holiday, $holidayId, $name, $observed);
            }
        };
    }
}
