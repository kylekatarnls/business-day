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
     * Set the holidays region (see src/Cmixin/Holidays for examples).
     *
     * @return \Closure
     */
    public function setHolidaysRegion()
    {
        $mixin = $this;

        return function ($region) use ($mixin) {
            $region = preg_replace('/[^a-zA-Z0-9_-]/', '', $region);
            $mixin->holidaysRegion = $region;
            if (!isset($mixin->holidays[$region]) && file_exists($file = __DIR__."/../Holidays/$region.php")) {
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
            $region = is_string($region) ? $region : $mixin->holidaysRegion;
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
     * Initialize holidays region storage.
     *
     * @param string|null $region
     *
     * @return \Closure|$this
     */
    public function initializeHolidaysRegion($region = null)
    {
        if ($region) {
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
     * Add holidays to the holidays list.
     *
     * @return \Closure
     */
    public function pushHoliday()
    {
        $mixin = $this;

        return function ($region, $holiday, $key = null) use ($mixin) {
            $mixin->initializeHolidaysRegion($region);

            if (is_string($key)) {
                $mixin->holidays[$region][$key] = $holiday;

                return isset($this) ? $this : null;
            }

            $mixin->holidays[$region][] = $holiday;

            return isset($this) ? $this : null;
        };
    }

    /**
     * Add holidays to the holidays list.
     *
     * @return \Closure
     */
    public function addHoliday()
    {
        $mixin = $this;

        return function ($region, $holiday, $key = null, $name = null, $observed = null) use ($mixin) {
            static $dictionary, $observer;

            $mixin->initializeHolidaysRegion($region);
            $push = $mixin->pushHoliday();
            $push($region, $holiday, $key);

            if (isset($name) && $mixin instanceof Holiday) {
                if (!isset($dictionary)) {
                    $dictionary = $mixin->getHolidayNamesDictionary();
                }
                foreach ($name as $language => $text) {
                    $dictionary($language);
                    $mixin->holidayNames[$language][$key] = $text;
                }
            }

            if (isset($observed) && $mixin instanceof HolidayObserver) {
                if (!isset($observer)) {
                    $observer = $mixin->setHolidayObserveStatus();
                }
                $observer($key, $observed);
            }

            return isset($this) ? $this : null;
        };
    }

    /**
     * Add holidays to the holidays list.
     *
     * @return \Closure
     */
    public function unpackHoliday()
    {
        $mixin = $this;

        return function (&$holiday, &$name = null, &$observed = null) use ($mixin) {
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
     * Add holidays to the holidays list.
     *
     * @return \Closure
     */
    public function addHolidays()
    {
        $mixin = $this;

        return function ($region, $holidays) use ($mixin) {
            $mixin->initializeHolidaysRegion($region);
            $add = $mixin->addHoliday();
            $unpack = $mixin->unpackHoliday();

            foreach ($holidays as $key => $holiday) {
                $name = null;
                $observed = null;
                if (is_array($holiday)) {
                    if (is_int($key)) {
                        throw new \InvalidArgumentException(
                            'Holiday array definition need a string identifier as main array key.'
                        );
                    }

                    $unpack($holiday, $name, $observed);
                }

                $add($region, $holiday, $key, $name, $observed);
            }
        };
    }
}
