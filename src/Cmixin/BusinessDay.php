<?php

namespace Cmixin;

use Carbon\Carbon;

class BusinessDay
{
    protected static $carbonClass = null;

    public $holidays = [];

    public $holidaysRegion = null;

    protected static function getCarbonClass()
    {
        return static::$carbonClass ?: 'Carbon\Carbon';
    }

    public static function enable($carbonClass = null)
    {
        if ($carbonClass === null) {
            return function () {
                return true;
            };
        }

        static::$carbonClass = $carbonClass;
        $carbonClass = static::getCarbonClass();
        $mixin = new static();

        $carbonClass::mixin($mixin);

        return $mixin;
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

        return function () use ($mixin) {
            $region = $mixin->holidaysRegion;
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
     * Checks the date to see if it is an holiday.
     *
     * @return \Closure
     */
    public function isHoliday()
    {
        $carbonClass = static::getCarbonClass();

        return function ($self = null) use ($carbonClass) {
            /** @var Carbon $self */
            $self = $self ?: $carbonClass::now();
            $holidays = $carbonClass::getHolidays();
            $date = $self->format('d/m');
            foreach ($holidays as $holiday) {
                if (is_callable($holiday)) {
                    $holiday = call_user_func($holiday, $self->year);
                }

                if ($date === $holiday) {
                    return true;
                }
            }

            return false;
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
            /** @var Carbon|BusinessDay $self */
            $self = $self ?: $carbonClass::now();

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

        return function ($self = null) use ($carbonClass) {
            /** @var Carbon|BusinessDay $self */
            $self = $self ? $self->copy() : $carbonClass::now();

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

        return function ($self = null) use ($carbonClass) {
            /** @var Carbon|BusinessDay $self */
            $self = $self ? $self->copy() : $carbonClass::now();

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

        return function ($self = null) use ($carbonClass) {
            /** @var Carbon|BusinessDay $self */
            $self = $self ? $self->copy() : $carbonClass::now();

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

        return function ($self = null) use ($carbonClass) {
            /** @var Carbon|BusinessDay $self */
            $self = $self ? $self->copy() : $carbonClass::now();

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

        return function ($days = 1, $self = null) use ($carbonClass) {
            if ($days instanceof \DateTime || $days instanceof \DateTimeInterface) {
                $self = $days;
                $days = 1;
            }
            /** @var Carbon|BusinessDay $self */
            $self = $self ?: $carbonClass::now();

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

        return function ($days = 1, $self = null) use ($carbonClass) {
            if ($days instanceof \DateTime || $days instanceof \DateTimeInterface) {
                $self = $days;
                $days = 1;
            }
            /** @var Carbon|BusinessDay $self */
            $self = $self ?: $carbonClass::now();

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

        return function ($days = 1, $self = null) use ($carbonClass) {
            if ($days instanceof \DateTime || $days instanceof \DateTimeInterface) {
                $self = $days;
                $days = 1;
            }
            /** @var Carbon|BusinessDay $self */
            $self = $self ?: $carbonClass::now();

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

        return function ($days = 1, $self = null) use ($carbonClass) {
            if ($days instanceof \DateTime || $days instanceof \DateTimeInterface) {
                $self = $days;
                $days = 1;
            }
            /** @var Carbon|BusinessDay $self */
            $self = $self ?: $carbonClass::now();

            return $self->subBusinessDays($days);
        };
    }
}
