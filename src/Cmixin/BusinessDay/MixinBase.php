<?php

namespace Cmixin\BusinessDay;

abstract class MixinBase
{
    protected static $carbonClass = null;

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

        $arguments = func_get_args();

        if (isset($arguments[1]) && is_string($region = $arguments[1])) {
            $carbonClass::setHolidaysRegion($region);

            if (isset($arguments[2])) {
                $carbonClass::addHolidays($region, $arguments[2]);
            }
        }

        return $mixin;
    }

    /**
     * Return current context $this or Carbon::today() if called statically.
     *
     * @return \Closure
     */
    public function getThisOrToday()
    {
        $staticClass = static::getCarbonClass();

        /**
         * Return current context $this or Carbon::today() if called statically.
         *
         * @param \Carbon\CarbonInterface $self
         * @param \Carbon\CarbonInterface $context
         *
         * @return \Carbon\CarbonInterface|\Carbon\Carbon|\Carbon\CarbonImmutable
         */
        return function ($self, $context) use ($staticClass) {
            if (!isset($self) && isset($context)) {
                $self = $context;
            }

            return $self ?: $staticClass::today();
        };
    }

    /**
     * Return true if the given value is a DateTime or DateTimeInterface.
     *
     * @return \Closure
     */
    public function isDateTimeInstance()
    {
        /**
         * Return true if the given value is a DateTime or DateTimeInterface.
         *
         * @param mixed $value
         *
         * @return boolean
         */
        return function ($value) {
            return $value instanceof \DateTime || $value instanceof \DateTimeInterface;
        };
    }

    /**
     * Store a first variable as Carbon instance into the second variable if the first one is a date.
     *
     * @return \Closure
     */
    public function swapDateTimeParam()
    {
        $check = static::isDateTimeInstance();
        $staticClass = static::getCarbonClass();

        /**
         * Store a first variable as Carbon instance into the second variable if the first one is a date.
         *
         * @param mixed &$date        First variable to check if it's a date (DateTime or DateTimeInterface)
         * @param mixed &$target      Target variable that will be replaced by the first one if it's a date
         * @param mixed $defaultValue Value to store in the first variable if it's a date
         */
        return function (&$date, &$target, $defaultValue = null) use ($check, $staticClass) {
            if ($check($date)) {
                $target = $staticClass::instance($date);
                $date = $defaultValue;
            }
        };
    }
}
