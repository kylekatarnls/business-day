<?php

namespace Cmixin\BusinessDay\Calculator;

use Cmixin\BusinessDay\BusinessCalendar;
use DateTime;
use SplObjectStorage;

final class MixinConfigPropagator
{
    public static function propagate(BusinessCalendar $mixin, $from, $to): void
    {
        foreach ([
            $mixin->businessDayCheckers,
            $mixin->holidayGetters,
            $mixin->workdayGetters,
        ] as $config) {
            if ($config && isset($config[$from])) {
                $config[$to] = $config[$from];
            }
        }
    }

    public static function apply(BusinessCalendar $mixin, $date, $method)
    {
        $result = $date->$method();

        if (!($date instanceof DateTime)) {
            self::propagate($mixin, $date, $result);
        }

        return $result;
    }

    public static function setBusinessDayChecker(BusinessCalendar $mixin, $date, ?callable $checkCallback)
    {
        return self::setStrategy('businessDayChecker', $mixin, $date, $checkCallback);
    }

    public static function getBusinessDayChecker(BusinessCalendar $mixin, $date): ?callable
    {
        return self::getStrategy('businessDayChecker', $mixin, $date);
    }

    public static function setHolidayGetter(BusinessCalendar $mixin, $date, ?callable $holidayGetter)
    {
        return self::setStrategy('holidayGetter', $mixin, $date, $holidayGetter);
    }

    public static function getHolidayGetter(BusinessCalendar $mixin, $date): ?callable
    {
        return self::getStrategy('holidayGetter', $mixin, $date);
    }

    public static function setExtraWorkdayGetter(BusinessCalendar $mixin, $date, ?callable $holidayGetter)
    {
        return self::setStrategy('workdayGetter', $mixin, $date, $holidayGetter);
    }

    public static function getExtraWorkdayGetter(BusinessCalendar $mixin, $date): ?callable
    {
        return self::getStrategy('workdayGetter', $mixin, $date);
    }

    private static function setStrategy(string $strategy, BusinessCalendar $mixin, $date, ?callable $callback)
    {
        $storage = $date ?? $mixin;

        if (!$date) {
            $storage->$strategy = $callback;

            return null;
        }

        // If mutable
        if ($date instanceof DateTime) {
            $date->$strategy = $callback;

            return $date;
        }

        $plural = $strategy.'s';

        if (!$mixin->$plural) {
            $mixin->$plural = new SplObjectStorage();
        }

        $mixin->$plural[$date] = $callback;

        return $date;
    }

    private static function getStrategy(string $strategy, BusinessCalendar $mixin, $date): ?callable
    {
        if ($date && isset($date->$strategy)) {
            return $date->$strategy;
        }

        $plural = $strategy.'s';

        return $mixin->$plural[$date] ?? $mixin->$strategy;
    }
}
