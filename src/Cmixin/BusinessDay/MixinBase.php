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
}
