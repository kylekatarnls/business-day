<?php

namespace Cmixin\BusinessDay\Calculator;

use Carbon\CarbonInterface;
use Cmixin\BusinessDay\BusinessCalendar;
use DateTime;
use SplObjectStorage;

final class MixinConfigPropagator
{
    private static $storage = [];

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
        if ($date instanceof CarbonInterface) {
            return $date->settings(['macros' => [
                '__bd_strategy_'.$strategy => $callback,
            ]]);
        }

        if (!isset(static::$storage[$strategy])) {
            static::$storage[$strategy] = new SplObjectStorage();
        }

        static::$storage[$strategy]->offsetSet($date ?? $mixin, $callback);

        return $date;
    }

    private static function getStrategy(string $strategy, BusinessCalendar $mixin, $date): ?callable
    {
        if ($date instanceof CarbonInterface && ($callback = $date->getLocalMacro('__bd_strategy_'.$strategy))) {
            return $callback;
        }

        $storage = static::$storage[$strategy] ?? null;

        if (!$storage) {
            return null;
        }

        return ($date ? $storage[$date] ?? null : null)
            ?? $storage[$mixin]
            ?? null;
    }
}
