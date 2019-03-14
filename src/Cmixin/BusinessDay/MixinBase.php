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

    public function getThisOrToday()
    {
        $staticClass = static::getCarbonClass();

        return function ($self, $context) use ($staticClass) {
            if (!isset($self) && isset($context)) {
                $self = $context;
            }

            return $self ?: $staticClass::today();
        };
    }

    public function isDateTimeInstance()
    {
        return function ($value) {
            return $value instanceof \DateTime || $value instanceof \DateTimeInterface;
        };
    }

    public function swapDateTimeParam()
    {
        $check = static::isDateTimeInstance();
        $staticClass = static::getCarbonClass();

        return function (&$date, &$target, $defaultValue = null) use ($check, $staticClass) {
            if ($check($date)) {
                $target = $staticClass::instance($date);
                $date = $defaultValue;
            }
        };
    }
}
