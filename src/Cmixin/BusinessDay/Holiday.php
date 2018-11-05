<?php

namespace Cmixin\BusinessDay;

use Carbon\Carbon;
use Cmixin\BusinessDay;

class Holiday extends HolidaysList
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
        $carbonClass = static::getCarbonClass();
        $getThisOrToday = static::getThisOrToday();

        return function ($self = null) use ($carbonClass, $getThisOrToday) {
            /** @var Carbon|BusinessDay $self */
            $self = $getThisOrToday($self, isset($this) ? $this : null);

            $holidays = $carbonClass::getHolidays();
            $date = $self->format('d/m');
            foreach ($holidays as $key => $holiday) {
                if (is_callable($holiday)) {
                    $holiday = call_user_func($holiday, $self->year);
                }

                if ($date === $holiday) {
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
        $getThisOrToday = static::getThisOrToday();

        return function ($self = null) use ($getThisOrToday) {
            /** @var Carbon|BusinessDay $self */
            $self = $getThisOrToday($self, isset($this) ? $this : null);

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
        $carbonClass = static::getCarbonClass();
        $getThisOrToday = static::getThisOrToday();
        $swap = static::swapDateTimeParam();
        $dictionary = $this->getHolidayNamesDictionary();

        return function ($locale = null, $self = null) use ($carbonClass, $getThisOrToday, $swap, $dictionary) {
            $swap($locale, $self);

            /** @var Carbon|BusinessDay $self */
            $self = $getThisOrToday($self, isset($this) ? $this : null);
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
