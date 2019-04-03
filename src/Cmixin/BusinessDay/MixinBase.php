<?php

namespace Cmixin\BusinessDay;

abstract class MixinBase
{
    /**
     * @deprecated
     *
     * @var string
     */
    protected static $carbonClass = null;

    /**
     * @deprecated
     *
     * Returns the last class name enabled via static facade.
     *
     * @return string
     */
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

        $isArray = is_array($carbonClass);
        $carbonClasses = (array) $carbonClass;
        $mixins = array();

        foreach ($carbonClasses as $carbonClass) {
            static::$carbonClass = $carbonClass;
            $mixin = new static();
            $carbonClass::mixin($mixin);
            $arguments = func_get_args();

            if (isset($arguments[1]) && is_string($region = $arguments[1])) {
                $carbonClass::setHolidaysRegion($region);

                if (isset($arguments[2])) {
                    $carbonClass::addHolidays($region, $arguments[2]);
                }
            }
        }

        return $isArray ? $mixins : $mixin;
    }

    /**
     * Return current context $this or Carbon::today() if called statically.
     *
     * @return \Closure
     */
    public function getThisOrToday()
    {
        /**
         * Return current context $this or Carbon::today() if called statically.
         *
         * @param \Carbon\CarbonInterface $self
         * @param \Carbon\CarbonInterface $context
         *
         * @return \Carbon\CarbonInterface|\Carbon\Carbon|\Carbon\CarbonImmutable
         */
        return function ($self, $context) {
            $carbonClass = @get_class() ?: Emulator::getClass(new \Exception());

            if (!isset($self) && isset($context)) {
                $self = $context;
            }

            return $self ?: $carbonClass::today();
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
         * @return bool
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
        /**
         * Store a first variable as Carbon instance into the second variable if the first one is a date.
         *
         * @param mixed $date         First variable to check if it's a date (DateTime or DateTimeInterface)
         * @param mixed $target       Target variable that will be replaced by the first one if it's a date
         * @param mixed $defaultValue Value to store in the first variable if it's a date
         *
         * @return array the new pair of variables
         */
        return function ($date, $target, $defaultValue) {
            $carbonClass = @get_class() ?: Emulator::getClass(new \Exception());

            if ($carbonClass::isDateTimeInstance($date)) {
                $target = $carbonClass::instance($date);
                $date = $defaultValue;
            }

            return array($date, $target);
        };
    }
}
