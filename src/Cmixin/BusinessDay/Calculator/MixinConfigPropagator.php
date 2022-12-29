<?php

namespace Cmixin\BusinessDay\Calculator;

use Carbon\CarbonInterface;
use Cmixin\BusinessDay\BusinessCalendar;
use DateTime;
use SplObjectStorage;

final class MixinConfigPropagator
{
    private static $storage = [];

    public static function propagate($from, $to): void
    {
        foreach ([
            'businessDayChecker',
            'holidayGetter',
            'workdayGetter',
        ] as $config) {
            if (isset(self::$storage[$config][$from])) {
                self::$storage[$config][$to] = self::$storage[$config][$from];
            }
        }
    }

    public static function apply($date, $method)
    {
        $result = $date->$method();

        if (!($date instanceof DateTime)) {
            self::propagate($date, $result);
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

        if (!isset(self::$storage[$strategy])) {
            self::$storage[$strategy] = new SplObjectStorage();
        }

        self::$storage[$strategy]->offsetSet($date ?? $mixin, $callback);

        return $date;
    }

    private static function getStrategy(string $strategy, BusinessCalendar $mixin, $date): ?callable
    {
        if ($date instanceof CarbonInterface && ($callback = $date->getLocalMacro('__bd_strategy_'.$strategy))) {
            return $callback;
        }

        $storage = self::$storage[$strategy] ?? null;

        if (!$storage) {
            return null;
        }

        return ($date ? $storage[$date] ?? null : null)
            ?? $storage[$mixin]
            ?? null;
    }
}
