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
        /**
         * Add a given number of business days to the current date.
         *
         * @param int $days
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        return static function ($days = 1, $date = null) use ($factor) {
            /** @var Carbon|BusinessDay $self */
            $self = static::this();

            [$days, $date] = static::swapDateTimeParam($days, $date, null);
            $days = ($days ?? (is_object($date) ? null : $date)) ?? 1;
            $date = is_object($date) ? $self->resolveCarbon($date) : $self;

            $days *= $factor;

            for ($i = $days; $i > 0; $i--) {
                $date = $date->nextBusinessDay();
            }

            for ($i = $days; $i < 0; $i++) {
                $date = $date->previousBusinessDay();
            }

            return $date;
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
         * @param int $days
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
         * @param int $days
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
        /**
         * Returns the difference between 2 dates in business days.
         *
         * @param \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface $other other date
         *
         * @return int
         */
        return static function ($other = null) {
            /** @var Carbon|BusinessDay $self */
            $self = static::this();

            return $self->diffInDaysFiltered(static function ($date) {
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
        /**
         * Get the number of business days in the current month.
         *
         * @return int
         */
        return static function ($date = null) {
            $self = static::this();
            $month = new BusinessMonth($date ?? $self, get_class($self));

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
        /**
         * Get list of date objects for each business day in the current month.
         *
         * @return array
         */
        return static function ($date = null) {
            $self = static::this();
            $month = new BusinessMonth($date ?? $self, get_class($self));
            $date = $month->getStart();
            $dates = [];

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
