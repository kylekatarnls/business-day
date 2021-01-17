<?php

namespace Cmixin\BusinessDay;

use Cmixin\BusinessDay\Calculator\MixinConfigPropagator;
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
         *
         * @return $this|null
         */
        return static function (?callable $checkCallback = null) use ($mixin) {
            return MixinConfigPropagator::setBusinessDayChecker(
                $mixin,
                end(static::$macroContextStack) ?: null,
                $checkCallback
            );
        };
    }

    /**
     * Checks the date to see if it is a business day (extra workday or neither a weekend day nor a holiday).
     *
     * @return \Closure
     */
    public function isBusinessDay()
    {
        $mixin = $this;

        /**
         * Checks the date to see if it is a business day (extra workday or neither a weekend day nor a holiday).
         *
         * @return bool
         */
        return static function () use ($mixin) {
            /** @var \Carbon\Carbon|\Cmixin\BusinessDay $self */
            $self = static::this();
            $businessDayChecker = MixinConfigPropagator::getBusinessDayChecker($mixin, $self);

            if ($businessDayChecker) {
                return $businessDayChecker($self);
            }

            return $self->isExtraWorkday() || ($self->isWeekday() && !$self->isHoliday());
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
        return static function () use ($mixin, $method) {
            /** @var static $self */
            $self = static::this();

            do {
                $self = MixinConfigPropagator::apply($mixin, $self, $method);
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
        /**
         * Sets the date to the current or next business day (neither a weekend day nor a holiday).
         *
         * @return \Carbon\CarbonInterface|\Carbon\Carbon|\Carbon\CarbonImmutable
         */
        return static function () use ($method) {
            $self = static::this();

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
