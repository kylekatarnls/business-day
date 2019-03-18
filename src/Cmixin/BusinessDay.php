<?php

namespace Cmixin;

use Carbon\Carbon;
use Cmixin\BusinessDay\BusinessCalendar;
use Cmixin\BusinessDay\BusinessMonth;

class BusinessDay extends BusinessCalendar
{
    /**
     * Sets the date to that corresponds to the number of business days after the starting date.
     *
     * @return \Closure
     */
    public function addBusinessDays($factor = 1)
    {
        $mixin = $this;
        $getThisOrToday = static::getThisOrToday();
        $swap = static::swapDateTimeParam();

        return function ($days = 1, $self = null) use ($mixin, $factor, $getThisOrToday, $swap) {
            $swap($days, $self, 1);

            /** @var Carbon|BusinessDay $self */
            $self = $getThisOrToday($self, isset($this) && $this !== $mixin ? $this : null);

            $days *= $factor;

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
        return $this->addBusinessDays();
    }

    /**
     * Sets the date to that corresponds to the number of business days prior the starting date.
     *
     * @return \Closure
     */
    public function subBusinessDays()
    {
        return $this->addBusinessDays(-1);
    }

    /**
     * @alias subBusinessDays
     *
     * Sets the date to that corresponds to the number of business days prior the starting date.
     *
     * @return \Closure
     */
    public function subtractBusinessDays()
    {
        return $this->addBusinessDays(-1);
    }

    /**
     * Sets the date to that corresponds to the number of business days prior the starting date.
     *
     * @return \Closure
     */
    public function subBusinessDay()
    {
        return $this->subBusinessDays();
    }

    /**
     * @alias subBusinessDay
     *
     * Sets the date to that corresponds to the number of business days prior the starting date.
     *
     * @return \Closure
     */
    public function subtractBusinessDay()
    {
        return $this->subBusinessDays();
    }

    /**
     * Returns the difference between 2 dates in business days.
     *
     * @return \Closure
     */
    public function diffInBusinessDays()
    {
        $mixin = $this;
        $getThisOrToday = static::getThisOrToday();

        return function ($other = null, $self = null) use ($mixin, $getThisOrToday) {

            /** @var Carbon|BusinessDay $self */
            $self = $getThisOrToday($self, isset($this) && $this !== $mixin ? $this : null);

            return $self->diffInDaysFiltered(function ($date) {
                /* @var Carbon|static $date */

                return $date->isBusinessDay();
            }, $other);
        };
    }

    /**
     * Get the number of business days in the current month.
     *
     * @return \Closure
     */
    public function getBusinessDaysInMonth()
    {
        $mixin = $this;
        $getThisOrToday = static::getThisOrToday();
        $carbonClass = static::getCarbonClass();

        return function ($self = null) use ($mixin, $getThisOrToday, $carbonClass) {
            $month = new BusinessMonth(
                $getThisOrToday($self, isset($this) && $this !== $mixin ? $this : null),
                $carbonClass
            );

            return $month->getStart()->diffInBusinessDays($month->getEnd());
        };
    }

    /**
     * Get list of dates object for each business day in the current month.
     *
     * @return \Closure
     */
    public function getMonthBusinessDays()
    {
        $mixin = $this;
        $getThisOrToday = static::getThisOrToday();
        $carbonClass = static::getCarbonClass();

        return function ($self = null) use ($mixin, $getThisOrToday, $carbonClass) {
            $month = new BusinessMonth(
                $getThisOrToday($self, isset($this) && $this !== $mixin ? $this : null),
                $carbonClass
            );
            $date = $month->getStart();
            $dates = array();

            while ($date < $month->getEnd()) {
                if ($date->isBusinessDay()) {
                    $dates[] = $date->copy();
                }

                $date = $date->addDay();
            }

            return $dates;
        };
    }
}
