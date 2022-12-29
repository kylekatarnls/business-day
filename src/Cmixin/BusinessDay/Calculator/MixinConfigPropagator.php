<?php

namespace Cmixin\BusinessDay\Calculator;

use Carbon\CarbonInterface;
use Cmixin\BusinessDay\BusinessCalendar;
use DateTime;
use SplObjectStorage;

final class MixinConfigPropagator
{
    private const BUSINESS_DAY_CHECKER = 'businessDayChecker';

    private const HOLIDAY_GETTER = 'holidayGetter';

    private const WORKDAY_GETTER = 'workdayGetter';

    private const STRATEGY_MACRO_PREFIX = '__bd_strategy_';

    private static $storage = [];

    public static function propagate($from, $to): void
    {
        foreach ([
            self::BUSINESS_DAY_CHECKER,
            self::HOLIDAY_GETTER,
            self::WORKDAY_GETTER,
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
        return self::setStrategy(self::BUSINESS_DAY_CHECKER, $mixin, $date, $checkCallback);
    }

    public static function getBusinessDayChecker(BusinessCalendar $mixin, $date): ?callable
    {
        return self::getStrategy(self::BUSINESS_DAY_CHECKER, $mixin, $date);
    }

    public static function setHolidayGetter(BusinessCalendar $mixin, $date, ?callable $holidayGetter)
    {
        return self::setStrategy(self::HOLIDAY_GETTER, $mixin, $date, $holidayGetter);
    }

    public static function getHolidayGetter(BusinessCalendar $mixin, $date): ?callable
    {
        return self::getStrategy(self::HOLIDAY_GETTER, $mixin, $date);
    }

    public static function setExtraWorkdayGetter(BusinessCalendar $mixin, $date, ?callable $holidayGetter)
    {
        return self::setStrategy(self::WORKDAY_GETTER, $mixin, $date, $holidayGetter);
    }

    public static function getExtraWorkdayGetter(BusinessCalendar $mixin, $date): ?callable
    {
        return self::getStrategy(self::WORKDAY_GETTER, $mixin, $date);
    }

    private static function setStrategy(string $strategy, BusinessCalendar $mixin, $date, ?callable $callback)
    {
        if ($date instanceof CarbonInterface) {
            return $date->settings(['macros' => [
                self::STRATEGY_MACRO_PREFIX.$strategy => $callback,
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
        $callback = ($date instanceof CarbonInterface)
            ? $date->getLocalMacro(self::STRATEGY_MACRO_PREFIX.$strategy)
            : null;

        if ($callback) {
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
