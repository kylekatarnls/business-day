<?php

namespace Cmixin\BusinessDay;

use Carbon\Carbon;
use Cmixin\BusinessDay;

class Holiday extends YearCrawler
{
    const DEFAULT_HOLIDAY_LOCALE = 'en';

    public $holidayNames = array();

    /**
     * Get the identifier of the current holiday or false if it's not a holiday.
     *
     * @return \Closure
     */
    public function getHolidayId()
    {
        $mixin = $this;
        $getThisOrToday = static::getThisOrToday();
        $getNextFunction = static::getYearHolidaysNextFunction();

        return function ($self = null) use ($mixin, $getThisOrToday, $getNextFunction) {
            /** @var Carbon|BusinessDay $self */
            $self = $getThisOrToday($self, isset($this) && $this !== $mixin ? $this : null);

            $date = $self->format('d/m');
            $year = $self->year;

            $next = $getNextFunction($year, 'string', $self);

            while ($data = $next()) {
                list($key, $holiday) = $data;

                if ($holiday && $date.(strlen($holiday) > 5 ? "/$year" : '') === $holiday) {
                    return $key;
                }
            }

            return false;
        };
    }

    /**
     * Checks the date to see if it is a holiday.
     *
     * @return \Closure
     */
    public function isHoliday()
    {
        $mixin = $this;
        $getThisOrToday = static::getThisOrToday();

        return function ($self = null) use ($mixin, $getThisOrToday) {
            /** @var Carbon|BusinessDay $self */
            $self = $getThisOrToday($self, isset($this) && $this !== $mixin ? $this : null);

            return $self->getHolidayId() !== false;
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

        return function ($locale) use ($mixin, $defaultLocale) {
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
        $mixin = $this;
        $carbonClass = static::getCarbonClass();
        $getThisOrToday = static::getThisOrToday();
        $swap = static::swapDateTimeParam();
        $dictionary = $this->getHolidayNamesDictionary();

        return function ($locale = null, $self = null) use ($mixin, $carbonClass, $getThisOrToday, $swap, $dictionary) {
            $swap($locale, $self);

            /** @var Carbon|BusinessDay $self */
            $self = $getThisOrToday($self, isset($this) && $this !== $mixin ? $this : null);
            $key = $self->getHolidayId();

            if ($key === false) {
                return false;
            }

            if (!$locale) {
                $locale = (isset($self->locale) ? $self->locale : $carbonClass::getLocale()) ?: 'en';
            }

            /* @var string $key */
            $names = $dictionary(preg_replace('/^([^_-]+)([_-].*)$/', '$1', $locale));

            return isset($names[$key]) ? $names[$key] : 'Unknown';
        };
    }
}
