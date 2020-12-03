<?php

namespace Cmixin\BusinessDay;

use DateTime;
use Exception;
use SplObjectStorage;

class BusinessCalendar extends HolidayObserver
{
    /**
     * @var callable|null
     */
    public $businessDayChecker = null;

    /**
     * @var SplObjectStorage<object,callable>|null
     */
    public $businessDayCheckers = null;

    /**
     * Change the way to check if a date is a business day.
     *
     * @return \Closure
     */
    public function setBusinessDayChecker()
    {
        $mixin = $this;

        /**
         * Checks the date to see if it is a business day (neither a weekend day nor a holiday).
         *
         * @param callable|null $checkCallback
         * @param object|null   $self
         *
         * @return $this|null
         */
        return function (?callable $checkCallback = null, $self = null) use ($mixin) {
            $date = isset($this) && $this !== $mixin ? $this : $self;
            $storage = $date ?? $mixin;

            if (!$date) {
                $storage->businessDayChecker = $checkCallback;

                return null;
            }

            // If mutable
            if ($date instanceof DateTime) {
                $date->businessDayChecker = $checkCallback;

                return $date;
            }

            if (!$mixin->businessDayCheckers) {
                $mixin->businessDayCheckers = new SplObjectStorage();
            }

            $mixin->businessDayCheckers[$date] = $checkCallback;

            return $date;
        };
    }

    /**
     * Checks the date to see if it is a business day (neither a weekend day nor a holiday).
     *
     * @return \Closure
     */
    public function isBusinessDay()
    {
        $mixin = $this;

        /**
         * Checks the date to see if it is a business day (neither a weekend day nor a holiday).
         *
         * @return bool
         */
        return function ($self = null) use ($mixin) {
            $carbonClass = @get_class() ?: Emulator::getClass(new Exception());

            /** @var \Carbon\Carbon|\Cmixin\BusinessDay $self */
            $self = $carbonClass::getThisOrToday($self, isset($this) && $this !== $mixin ? $this : null);
            $date = isset($this) && $this !== $mixin ? $this : $self;
            $businessDayChecker = $date && isset($date->businessDayChecker)
                ? $date->businessDayChecker
                : ($mixin->businessDayCheckers[$date] ?? $mixin->businessDayChecker);

            if ($businessDayChecker) {
                return $businessDayChecker($self);
            }

            return $self->isWeekday() && !$self->isHoliday();
        };
    }

    /**
     * Sets the date to the next business day (neither a weekend day nor a holiday).
     *
     * @param string $method addDay() method by default
     *
     * @return \Closure
     */
    public function nextBusinessDay($method = 'addDay')
    {
        $mixin = $this;

        /**
         * Sets the date to the next business day (neither a weekend day nor a holiday).
         *
         * @return \Carbon\CarbonInterface|\Carbon\Carbon|\Carbon\CarbonImmutable
         */
        return function ($self = null) use ($mixin, $method) {
            $carbonClass = @get_class() ?: Emulator::getClass(new Exception());

            /** @var static $self */
            $self = $carbonClass::getThisOrToday($self, isset($this) && $this !== $mixin ? $this : null);

            do {
                if (!($self instanceof DateTime)) {
                    $original = $self;
                }

                $self = $self->$method();

                if (isset($original)) {
                    foreach ([$mixin->businessDayCheckers, $mixin->holidayGetters] as $config) {
                        if ($config && isset($config[$original])) {
                            $config[$self] = $config[$original];
                        }
                    }
                }
            } while (!$self->isBusinessDay());

            return $self;
        };
    }

    /**
     * Sets the date to the current or next business day (neither a weekend day nor a holiday).
     *
     * @param string $method addDay() method by default
     *
     * @return \Closure
     */
    public function currentOrNextBusinessDay($method = 'nextBusinessDay')
    {
        $mixin = $this;

        /**
         * Sets the date to the current or next business day (neither a weekend day nor a holiday).
         *
         * @return \Carbon\CarbonInterface|\Carbon\Carbon|\Carbon\CarbonImmutable
         */
        return function ($self = null) use ($mixin, $method) {
            $carbonClass = @get_class() ?: Emulator::getClass(new Exception());

            $self = $carbonClass::getThisOrToday($self, isset($this) && $this !== $mixin ? $this : null);

            return $self->isBusinessDay() ? $self : $self->$method();
        };
    }

    /**
     * Sets the date to the previous business day (neither a weekend day nor a holiday).
     *
     * @return \Closure
     */
    public function previousBusinessDay()
    {
        /**
         * Sets the date to the previous business day (neither a weekend day nor a holiday).
         *
         * @return \Carbon\CarbonInterface|\Carbon\Carbon|\Carbon\CarbonImmutable
         */
        return $this->nextBusinessDay('subDay');
    }

    /**
     * Sets the date to the current or previous business day.
     *
     * @return \Closure
     */
    public function currentOrPreviousBusinessDay()
    {
        /**
         * Sets the date to the current or previous business day.
         *
         * @return \Carbon\CarbonInterface|\Carbon\Carbon|\Carbon\CarbonImmutable
         */
        return $this->currentOrNextBusinessDay('previousBusinessDay');
    }
}
