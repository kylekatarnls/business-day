<?php

namespace Cmixin\BusinessDay;

use Carbon\Carbon;
use Cmixin\BusinessDay;
use Cmixin\BusinessDay\Calculator\MixinConfigPropagator;
use SplObjectStorage;

class Holiday extends YearCrawler
{
    use HolidayData;

    const DEFAULT_HOLIDAY_LOCALE = 'en';

    /**
     * @var array
     */
    public $holidayNames = [];

    /**
     * @var callable|null
     */
    public $holidayGetter = null;

    /**
     * @var SplObjectStorage<object,callable>|null
     */
    public $holidayGetters = null;

    /**
     * @var callable|null
     */
    public $workdayGetter = null;

    /**
     * @var SplObjectStorage<object,callable>|null
     */
    public $workdayGetters = null;

    /**
     * Set the strategy to get the holiday ID from a date object.
     *
     * @return \Closure
     */
    public function setHolidayGetter()
    {
        $mixin = $this;

        /**
         * Set the strategy to get the holiday ID from a date object.
         *
         * @param callable|null $holidayGetter
         *
         * @return $this|null
         */
        return static function (?callable $holidayGetter) use ($mixin) {
            return MixinConfigPropagator::setHolidayGetter(
                $mixin,
                end(static::$macroContextStack) ?: null,
                $holidayGetter
            );
        };
    }

    /**
     * Set the strategy to get the extra workday ID from a date object.
     *
     * @return \Closure
     */
    public function setExtraWorkdayGetter()
    {
        $mixin = $this;

        /**
         * Set the strategy to get the extra workday ID from a date object.
         *
         * @param callable|null $workdayGetter
         *
         * @return $this|null
         */
        return static function (?callable $workdayGetter) use ($mixin) {
            return MixinConfigPropagator::setExtraWorkdayGetter(
                $mixin,
                end(static::$macroContextStack) ?: null,
                $workdayGetter
            );
        };
    }

    /**
     * Get the identifier of the current holiday or false if it's not a holiday.
     *
     * @return \Closure
     */
    public function getDBDayId()
    {
        /**
         * Get the identifier of the current holiday or false if it's not a holiday.
         *
         * @return string|false
         */
        return static function (string $getDays = 'getHolidays') {
            /** @var Carbon|BusinessDay $self */
            $self = static::this();

            $date = $self->format('d/m');
            $year = $self->year;

            $next = $self->getYearHolidaysNextFunction($year, 'string', $getDays);

            while ($data = $next()) {
                [$holidayId, $holiday] = $data;

                if ($holiday && $date.(strlen($holiday) > 5 ? "/$year" : '') === $holiday) {
                    return $holidayId;
                }
            }

            return false;
        };
    }

    /**
     * Get the identifier of the current holiday or false if it's not a holiday.
     *
     * @return \Closure
     */
    public function getHolidayId()
    {
        $mixin = $this;

        /**
         * Get the identifier of the current holiday or false if it's not a holiday.
         *
         * @return string|false
         */
        return static function () use ($mixin) {
            /** @var Carbon|BusinessDay $self */
            $self = static::this();

            $fallback = function () use ($self) {
                return $self->getDBDayId();
            };

            $holidayGetter = MixinConfigPropagator::getHolidayGetter($mixin, $self);

            return $holidayGetter
                ? $holidayGetter($mixin->holidaysRegion, $self, $fallback)
                : $fallback();
        };
    }

    /**
     * Checks the date to see if it is a holiday.
     *
     * @return \Closure
     */
    public function isHoliday()
    {
        /**
         * Checks the date to see if it is a holiday.
         *
         * @return bool
         */
        return static function () {
            /** @var Carbon|BusinessDay $self */
            $self = static::this();

            return $self->getHolidayId() !== false;
        };
    }

    /**
     * Get the identifier of the current special workday or false if it's not a special workday.
     *
     * @return \Closure
     */
    public function getExtraWorkdayId()
    {
        $mixin = $this;

        /**
         * Get the identifier of the current special workday or false if it's not a special workday.
         *
         * @return string|false
         */
        return static function () use ($mixin) {
            /** @var Carbon|BusinessDay $self */
            $self = static::this();

            $fallback = function () use ($self) {
                return $self->getDBDayId('getExtraWorkdays');
            };

            $workdayGetter = MixinConfigPropagator::getExtraWorkdayGetter($mixin, $self);

            return $workdayGetter
                ? $workdayGetter($mixin->holidaysRegion, $self, $fallback)
                : $fallback();
        };
    }

    /**
     * Checks the date to see if it is a holiday.
     *
     * @return \Closure
     */
    public function isExtraWorkday()
    {
        /**
         * Checks the date to see if it is a holiday.
         *
         * @return bool
         */
        return static function () {
            /** @var Carbon|BusinessDay $self */
            $self = static::this();

            return $self->getExtraWorkdayId() !== false;
        };
    }

    /**
     * Get the holidays in the given language.
     *
     * @return \Closure
     */
    public function getHolidayNamesDictionary()
    {
        $mixin = $this;
        $defaultLocale = static::DEFAULT_HOLIDAY_LOCALE;

        /**
         * Get the holidays in the given language.
         *
         * @param string $locale language
         *
         * @return array
         */
        return static function ($locale) use ($mixin, $defaultLocale) {
            if (isset($mixin->holidayNames[$locale])) {
                return $mixin->holidayNames[$locale] ?: $mixin->holidayNames[$defaultLocale];
            }

            $file = __DIR__."/../HolidayNames/$locale.php";

            if (!file_exists($file)) {
                $mixin->holidayNames[$locale] = false;
                $locale = $defaultLocale;
                $file = __DIR__."/../HolidayNames/$locale.php";

                if (isset($mixin->holidayNames[$locale])) {
                    return $mixin->holidayNames[$locale];
                }
            }

            return $mixin->holidayNames[$locale] = include $file;
        };
    }

    /**
     * Get the name of the current holiday (using the locale given in parameter or the current date locale)
     * or false if it's not a holiday.
     *
     * @return \Closure
     */
    public function getHolidayName()
    {
        $dictionary = $this->getHolidayNamesDictionary();

        /**
         * Get the name of the current holiday (using the locale given in parameter or the current date locale)
         * or false if it's not a holiday.
         *
         * @param string $locale language ("en" by default)
         *
         * @return string|false
         */
        return static function ($date = null, $locale = null) use ($dictionary) {
            /** @var Carbon|BusinessDay $self */
            $self = static::this();
            /** @var Carbon|BusinessDay $date */
            [$locale, $date] = static::swapDateTimeParam($locale, $date, null);
            $locale = $locale ?? (is_string($date) ? $date : null);
            $date = is_object($date) ? $self->resolveCarbon($date) : $self;
            $holidayId = $date->getHolidayId();

            if ($holidayId === false) {
                return false;
            }

            if (!$locale) {
                $locale = ($date->locale ?? get_class($date)::getLocale()) ?: 'en';
            }

            /* @var string $holidayId */
            $names = $dictionary(preg_replace('/^([^_-]+)([_-].*)$/', '$1', $locale));

            return isset($names[$holidayId]) ? $names[$holidayId] : 'Unknown';
        };
    }
}
