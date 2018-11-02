<?php

namespace Cmixin\Traits;

trait HolidaysList
{
    public $holidays = array();

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
            $mixin->holidays[$region] = $holidays;
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
     * @return \Closure|$this
     */
    public function initializeHolidaysRegion($region = null)
    {
        if ($region) {
            if (!isset($this->holidays[$region])) {
                $this->holidays[$region] = array();
            }
            if ($this->holidays[$region] instanceof \Traversable) {
                $this->holidays[$region] = iterator_to_array($this->holidays[$region]);
            }

            return $this;
        }

        return function () {
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

            foreach ($holidays as $holiday) {
                $mixin->holidays[$region][] = $holiday;
            }
        };
    }
}
