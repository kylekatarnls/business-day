<?php

namespace Cmixin;

use Carbon\Carbon;

class BusinessDay extends EnableFacadeMixinBase
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
            if (!isset($mixin->holidays[$region]) && file_exists($file = __DIR__."/Holidays/$region.php")) {
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
     * Add holidays to the holidays list.
     *
     * @return \Closure
     */
    public function addHolidays()
    {
        $mixin = $this;

        return function ($region, $holidays) use ($mixin) {
            if (!isset($mixin->holidays[$region])) {
                $mixin->holidays[$region] = array();
            }
            if ($mixin->holidays[$region] instanceof \Traversable) {
                $mixin->holidays[$region] = iterator_to_array($mixin->holidays[$region]);
            }

            foreach ($holidays as $holiday) {
                $mixin->holidays[$region][] = $holiday;
            }
        };
    }

    /**
     * Get the identifier of the current holiday or false if it's not an holiday.
     *
     * @return \Closure
     */
    public function getHolidayId()
    {
        $carbonClass = static::getCarbonClass();
        $getThisOrToday = static::getThisOrToday();

        return function ($self = null) use ($carbonClass, $getThisOrToday) {
            /** @var Carbon|BusinessDay $self */
            $self = $getThisOrToday($self, isset($this) ? $this : null);

            $holidays = $carbonClass::getHolidays();
            $date = $self->format('d/m');
            foreach ($holidays as $key => $holiday) {
                if (is_callable($holiday)) {
                    $holiday = call_user_func($holiday, $self->year);
                }

                if ($date === $holiday) {
                    return $key;
                }
            }

            return false;
        };
    }

    /**
     * Checks the date to see if it is an holiday.
     *
     * @return \Closure
     */
    public function isHoliday()
    {
        $carbonClass = static::getCarbonClass();

        return function ($self = null) use ($carbonClass) {
            if (!isset($self) && isset($this)) {
                $self = $this;
            }
            /** @var Carbon $self */
            $self = $self ?: $carbonClass::today();

            return $self->getHolidayId() !== false;
        };
    }

    /**
     * Checks the date to see if it is a business day.
     *
     * @return \Closure
     */
    public function isBusinessDay()
    {
        $carbonClass = static::getCarbonClass();

        return function ($self = null) use ($carbonClass) {
            if (!isset($self) && isset($this)) {
                $self = $this;
            }
            /** @var Carbon|BusinessDay $self */
            $self = $self ?: $carbonClass::today();

            return $self->isWeekday() && !$self->isHoliday();
        };
    }

    /**
     * Sets the date to the next business day.
     *
     * @return \Closure
     */
    public function nextBusinessDay()
    {
        $carbonClass = static::getCarbonClass();
        $getThisOrToday = static::getThisOrToday();

        return function ($self = null) use ($carbonClass, $getThisOrToday) {
            /** @var Carbon|BusinessDay $self */
            $self = $getThisOrToday($self, isset($this) ? $this : null);

            do {
                $self->addDay();
            } while (!$self->isBusinessDay());

            return $self;
        };
    }

    /**
     * Sets the date to the current or next business day.
     *
     * @return \Closure
     */
    public function currentOrNextBusinessDay()
    {
        $carbonClass = static::getCarbonClass();
        $getThisOrToday = static::getThisOrToday();

        return function ($self = null) use ($carbonClass, $getThisOrToday) {
            /** @var Carbon|BusinessDay $self */
            $self = $getThisOrToday($self, isset($this) ? $this : null);

            return $self->isBusinessDay() ? $self : $self->nextBusinessDay();
        };
    }

    /**
     * Sets the date to the previous business day.
     *
     * @return \Closure
     */
    public function previousBusinessDay()
    {
        $carbonClass = static::getCarbonClass();
        $getThisOrToday = static::getThisOrToday();

        return function ($self = null) use ($carbonClass, $getThisOrToday) {
            /** @var Carbon|BusinessDay $self */
            $self = $getThisOrToday($self, isset($this) ? $this : null);

            do {
                $self->subDay();
            } while (!$self->isBusinessDay());

            return $self;
        };
    }

    /**
     * Sets the date to the current or previous business day.
     *
     * @return \Closure
     */
    public function currentOrPreviousBusinessDay()
    {
        $carbonClass = static::getCarbonClass();
        $getThisOrToday = static::getThisOrToday();

        return function ($self = null) use ($carbonClass, $getThisOrToday) {
            /** @var Carbon|BusinessDay $self */
            $self = $getThisOrToday($self, isset($this) ? $this : null);

            return $self->isBusinessDay() ? $self : $self->previousBusinessDay();
        };
    }

    /**
     * Sets the date to that corresponds to the number of business days after the starting date.
     *
     * @return \Closure
     */
    public function addBusinessDays()
    {
        $carbonClass = static::getCarbonClass();
        $getThisOrToday = static::getThisOrToday();

        return function ($days = 1, $self = null) use ($carbonClass, $getThisOrToday) {
            /** @var Carbon|BusinessDay $self */
            $self = $getThisOrToday($self, isset($this) ? $this : null);

            if ($days instanceof \DateTime || $days instanceof \DateTimeInterface) {
                $self = $days;
                $days = 1;
            }

            for ($i = $days; $i > 0; $i--) {
                $self = $self->nextBusinessDay();
            }

            for ($i = $days; $i < 0; $i++) {
                $self = $self->previousBusinessDay();
            }

            return $self;
        };
    }

    /**
     * Sets the date to that corresponds to the number of business days prior the starting date.
     *
     * @return \Closure
     */
    public function addBusinessDay()
    {
        $carbonClass = static::getCarbonClass();
        $getThisOrToday = static::getThisOrToday();

        return function ($days = 1, $self = null) use ($carbonClass, $getThisOrToday) {
            /** @var Carbon|BusinessDay $self */
            $self = $getThisOrToday($self, isset($this) ? $this : null);

            if ($days instanceof \DateTime || $days instanceof \DateTimeInterface) {
                $self = $days;
                $days = 1;
            }

            return $self->addBusinessDays($days);
        };
    }

    /**
     * Sets the date to that corresponds to the number of business days prior the starting date.
     *
     * @return \Closure
     */
    public function subBusinessDays()
    {
        $carbonClass = static::getCarbonClass();
        $getThisOrToday = static::getThisOrToday();

        return function ($days = 1, $self = null) use ($carbonClass, $getThisOrToday) {
            /** @var Carbon|BusinessDay $self */
            $self = $getThisOrToday($self, isset($this) ? $this : null);

            if ($days instanceof \DateTime || $days instanceof \DateTimeInterface) {
                $self = $days;
                $days = 1;
            }

            return $self->addBusinessDays(-$days);
        };
    }

    /**
     * Sets the date to that corresponds to the number of business days prior the starting date.
     *
     * @return \Closure
     */
    public function subBusinessDay()
    {
        $carbonClass = static::getCarbonClass();
        $getThisOrToday = static::getThisOrToday();

        return function ($days = 1, $self = null) use ($carbonClass, $getThisOrToday) {
            /** @var Carbon|BusinessDay $self */
            $self = $getThisOrToday($self, isset($this) ? $this : null);

            if ($days instanceof \DateTime || $days instanceof \DateTimeInterface) {
                $self = $days;
                $days = 1;
            }

            return $self->subBusinessDays($days);
        };
    }
}
