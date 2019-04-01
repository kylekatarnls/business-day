<?php

namespace Cmixin\BusinessDay;

class YearCrawler extends HolidaysList
{
    /**
     * Get the holidays dates for a the given year (current year if no parameter given).
     *
     * @return \Closure
     */
    public function getYearHolidays()
    {
        $getNextFunction = static::getYearHolidaysNextFunction();

        /**
         * Get the holidays dates for a given year (current year if no parameter given).
         *
         * @param int                                                            $year input year, year of the current instance or context used if omitted, current year used if omitted and called statically
         * @param string                                                         $type can be 'string' (to return dates as string) or a class name to returns instances of this class
         * @param \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface $self optional context
         *
         * @return array
         */
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

        /**
         * Get a next() callback to call to iterate over holidays of a year.
         *
         * @param int                                                            $year input year, year of the current instance or context used if omitted, current year used if omitted and called statically
         * @param string                                                         $type can be 'string' (to return dates as string) or a class name to returns instances of this class
         * @param \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface $self optional context
         *
         * @return \Closure
         */
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
