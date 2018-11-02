<?php

namespace Cmixin;

use Carbon\Carbon;
use Cmixin\Traits\Holiday;
use Cmixin\Traits\HolidaysList;

class BusinessDay extends EnableFacadeMixinBase
{
    use HolidaysList, Holiday;

    /**
     * Checks the date to see if it is a business day.
     *
     * @return \Closure
     */
    public function isBusinessDay()
    {
        $getThisOrToday = static::getThisOrToday();

        return function ($self = null) use ($getThisOrToday) {
            /** @var Carbon|BusinessDay $self */
            $self = $getThisOrToday($self, isset($this) ? $this : null);

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
        $getThisOrToday = static::getThisOrToday();

        return function ($self = null) use ($getThisOrToday) {
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
        $getThisOrToday = static::getThisOrToday();

        return function ($self = null) use ($getThisOrToday) {
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
        $getThisOrToday = static::getThisOrToday();

        return function ($self = null) use ($getThisOrToday) {
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
        $getThisOrToday = static::getThisOrToday();

        return function ($self = null) use ($getThisOrToday) {
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
        $getThisOrToday = static::getThisOrToday();

        return function ($days = 1, $self = null) use ($getThisOrToday) {
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
        $getThisOrToday = static::getThisOrToday();

        return function ($days = 1, $self = null) use ($getThisOrToday) {
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
        $getThisOrToday = static::getThisOrToday();

        return function ($days = 1, $self = null) use ($getThisOrToday) {
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
        $getThisOrToday = static::getThisOrToday();

        return function ($days = 1, $self = null) use ($getThisOrToday) {
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
