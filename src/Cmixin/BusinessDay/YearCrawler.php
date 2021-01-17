<?php

namespace Cmixin\BusinessDay;

use Cmixin\BusinessDay\Calculator\HolidayCalculator;

class YearCrawler extends HolidaysList
{
    /**
     * Get the holidays dates for a the given year (current year if no parameter given).
     *
     * @return \Closure
     */
    public function getYearHolidays()
    {
        /**
         * Get the holidays dates for a given year (current year if no parameter given).
         *
         * @param int    $year    input year, year of the current instance or context used if omitted, current year used if omitted and called statically
         * @param string $type    can be 'string' (to return dates as string) or a class name to returns instances of this class
         * @param string $getDays macro method to retrieve the days list
         *
         * @return array
         */
        return static function ($year = null, $type = null, string $getDays = 'getHolidays') {
            $carbonClass = get_class(static::this());
            $next = $carbonClass::getYearHolidaysNextFunction($year, $type, $getDays);
            $holidays = [];

            while ($data = $next()) {
                [$key, $holiday] = $data;

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
        /**
         * Get a next() callback to call to iterate over holidays of a year.
         *
         * @param int    $year    input year, year of the current instance or context used if omitted, current year used if omitted and called statically
         * @param string $type    can be 'string' (to return dates as string) or a class name to returns instances of this class
         * @param string $getDays macro method to retrieve the days list
         *
         * @return callable
         */
        return static function ($year = null, $type = null, string $getDays = 'getHolidays'): callable {
            $self = static::this();
            $carbonClass = get_class($self);
            $year = $year ?: $self->year;
            $holidays = $carbonClass::$getDays();
            $outputClass = $type ? (is_string($type) && $type !== 'string' ? $type : 'DateTime') : $carbonClass;
            $holidaysList = [];
            $calculator = new HolidayCalculator((int) $year, $type, $holidays);
            $calculator->setOutputClass($outputClass);
            $calculator->setHolidaysList($holidaysList);

            return [$calculator, 'next'];
        };
    }
}
