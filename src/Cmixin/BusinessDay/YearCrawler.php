<?php

namespace Cmixin\BusinessDay;

class YearCrawler extends HolidaysList
{
    /**
     * Get the holidays of the given year (current year if no parameter given).
     *
     * @return \Closure
     */
    public function getYearHolidays()
    {
        $getNextFunction = static::getYearHolidaysNextFunction();

        return function ($year = null, $type = null, $self = null) use ($getNextFunction) {
            $next = $getNextFunction($year, $type, $self);
            $holidays = array();

            while ($data = $next()) {
                list($key, $holiday) = $data;

                $holidays[$key] = $holiday;
            }

            return $holidays;
        };
    }

    /**
     * Get the holidays of the given year (current year if no parameter given).
     *
     * @return \Closure
     */
    public function getYearHolidaysNextFunction()
    {
        $mixin = $this;
        $carbonClass = static::getCarbonClass();
        $getThisOrToday = static::getThisOrToday();

        return function ($year = null, $type = null, $self = null) use ($mixin, $carbonClass, $getThisOrToday) {
            $year = $year ?: $getThisOrToday($self, isset($this) && $this !== $mixin ? $this : null)->year;
            $holidays = $carbonClass::getHolidays();
            $outputClass = $type ? (is_string($type) && $type !== 'string' ? $type : 'DateTime') : $carbonClass;
            $holidaysList = array();
            $calculator = new HolidayCalculator((int) $year, $outputClass, $type, $holidays, $holidaysList);

            return function () use ($calculator) {
                return $calculator->next();
            };
        };
    }
}
