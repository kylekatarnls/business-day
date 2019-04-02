<?php

namespace Cmixin;

use Carbon\Carbon;
use Cmixin\BusinessDay\BusinessCalendar;
use Cmixin\BusinessDay\BusinessMonth;

class BusinessDay extends BusinessCalendar
{
    /**
     * Add a given number of business days to the current date.
     *
     * @return \Closure
     */
    public function addBusinessDays($factor = 1)
    {
        $mixin = $this;
        $getThisOrToday = static::getThisOrToday();
        $swap = static::swapDateTimeParam();

        /**
         * Add a given number of business days to the current date.
         *
         * @param int                                                            $days
         * @param \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface $self optional context
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
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
     * Add one business day to the current date.
     *
     * @return \Closure
     */
    public function addBusinessDay()
    {
        /**
         * Add one business day to the current date.
         *
         * @param \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface $self optional context
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        return $this->addBusinessDays();
    }

    /**
     * Subtract a given number of business days to the current date.
     *
     * @return \Closure
     */
    public function subBusinessDays()
    {
        /**
         * Subtract a given number of business days to the current date.
         *
         * @param int                                                            $days
         * @param \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface $self optional context
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
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
        /**
         * Subtract a given number of business days to the current date.
         *
         * @param int                                                            $days
         * @param \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface $self optional context
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        return $this->addBusinessDays(-1);
    }

    /**
     * Subtract one business day to the current date.
     *
     * @return \Closure
     */
    public function subBusinessDay()
    {
        /**
         * Subtract one business day to the current date.
         *
         * @param \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface $self optional context
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        return $this->subBusinessDays();
    }

    /**
     * @alias subBusinessDay
     *
     * Subtract one business day to the current date.
     *
     * @return \Closure
     */
    public function subtractBusinessDay()
    {
        /**
         * Subtract one business day to the current date.
         *
         * @param \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface $self optional context
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
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

        /**
         * Returns the difference between 2 dates in business days.
         *
         * @param \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface $other other date
         * @param \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface $self  optional context
         *
         * @return int
         */
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

        /**
         * Get the number of business days in the current month.
         *
         * @param \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface $self optional context
         *
         * @return int
         */
        return function ($self = null) use ($mixin, $getThisOrToday, $carbonClass) {
            $month = new BusinessMonth(
                $getThisOrToday($self, isset($this) && $this !== $mixin ? $this : null),
                $carbonClass
            );

            return $month->getStart()->diffInBusinessDays($month->getEnd());
        };
    }

    /**
     * Get list of date objects for each business day in the current month.
     *
     * @return \Closure
     */
    public function getMonthBusinessDays()
    {
        $mixin = $this;
        $getThisOrToday = static::getThisOrToday();
        $carbonClass = static::getCarbonClass();

        /**
         * Get list of date objects for each business day in the current month.
         *
         * @param \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface $self optional context
         *
         * @return array
         */
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
