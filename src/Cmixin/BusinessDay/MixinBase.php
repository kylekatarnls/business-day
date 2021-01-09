<?php

namespace Cmixin\BusinessDay;

use Cmixin\BusinessDay\Util\DefinitionParser;
use DateTime;
use DateTimeInterface;

abstract class MixinBase
{
    public static function enable($carbonClass = null)
    {
        if ($carbonClass === null) {
            return static function () {
                return true;
            };
        }

        $isArray = is_array($carbonClass);
        $carbonClasses = (array) $carbonClass;
        $mixins = [];

        foreach ($carbonClasses as $carbonClass) {
            $mixin = new static();
            $carbonClass::mixin($mixin);
            $parser = new DefinitionParser(func_get_args());
            $parser->applyTo($carbonClass);
        }

        return $isArray ? $mixins : $mixin;
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
        return static function ($value) {
            return $value instanceof DateTime || $value instanceof DateTimeInterface;
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
         * @param mixed $target       Target variable that will be replaced by the first one if it's a date
         * @param mixed $date         Variable to check if it's a date (DateTime or DateTimeInterface)
         * @param mixed $defaultValue Value to store in the first variable if it's a date
         *
         * @return array the new pair of variables
         */
        return function ($target, $date, $defaultValue) {
            if (static::isDateTimeInstance($target)) {
                $date = static::instance($target);
                $target = $defaultValue;
            }

            return [$target, $date];
        };
    }
}
