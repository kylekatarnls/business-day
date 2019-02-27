<?php

namespace Cmixin\BusinessDay;

use Carbon\Carbon;
use Cmixin\BusinessDay;
use DateTime;

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
            $holidaysList = array();
            $date = $self->format('d/m');
            $year = $self->year;
            foreach ($holidays as $key => $holiday) {
                if (is_callable($holiday)) {
                    $holiday = call_user_func($holiday, $year);
                }

                if (!is_string($holiday)) {
                    continue;
                }

                if (substr($holiday, 0, 1) === '=') {
                    if ($substitute = preg_match('/\ssubstitute$/i', $holiday)) {
                        $holiday = trim(substr($holiday, 0, -11));
                    }

                    $holiday = preg_replace_callback('/(easter)/i', function ($match) use ($year) {
                        switch ($match[0]) {
                            case 'easter':
                                static $easterDays = array();

                                if (!isset($easterDays[$year])) {
                                    $easterDays[$year] = easter_days($year);
                                }

                                return "$year-03-21 $easterDays[$year] days ";
                        }
                    }, trim(substr($holiday, 1)));
                    $holiday = preg_replace('/^\d{2}-\d{2}(\s[\s\S]*)?$/', "$year-$0", $holiday);
                    $holiday = str_replace('$year', $year, $holiday);
                    $holiday = preg_replace('/(\s\d+)\s*$/', '$1 days', $holiday);
                    list($holiday, $condition) = array_pad(explode(' if ', $holiday, 2), 2, null);

                    if (strpos($holiday, "$year") === false) {
                        $holiday .= " $year";
                    }

                    $dateTime = new DateTime($holiday);

                    if ($condition) {
                        list($condition, $action) = array_pad(explode(' then ', $condition, 2), 2, null);
                        $condition = strtolower($condition);
                        $condition = $condition === 'weekend'
                            ? ($dateTime->format('N') > 5)
                            : (strtolower($dateTime->format('l')) === $condition);

                        if ($condition) {
                            $dateTime->modify($action);
                        }
                    }

                    while ($substitute && ($dateTime->format('N') > 5 || isset($holidaysList[$dateTime->format('d/m')]))) {
                        $dateTime->modify('+1 day');
                    }

                    $holiday = $dateTime->format('d/m');
                }

                if (strpos($holiday, '-') !== false) {
                    $holiday = preg_replace('/^(\d+)-(\d+)$/', '$2/$1', $holiday);
                    $holiday = preg_replace('/^(\d+)-(\d+)-(\d+)$/', '$3/$2/$1', $holiday);
                }

                $holidaysList[$holiday] = true;

                if ($date.(strlen($holiday) > 5 ? "/$year" : '') === $holiday) {
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
