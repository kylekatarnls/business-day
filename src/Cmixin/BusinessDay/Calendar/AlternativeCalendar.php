<?php

namespace Cmixin\BusinessDay\Calendar;

use Closure;

abstract class AlternativeCalendar implements AlternativeCalendarInterface
{
    /**
     * Base year is the closer delta between current Gregorian year (2019 used for ease) and current calendar year.
     * It allows to find faster a contemporary date.
     *
     * @var int
     */
    protected static $baseYear = 0;

    /**
     * @var AlternativeCalendar[]
     */
    protected static $singletons = array();

    /**
     * @var array
     */
    protected $months = array();

    /**
     * @var string
     */
    protected $regex;

    public static function get()
    {
        $name = get_called_class();

        if (!isset(static::$singletons[$name])) {
            static::$singletons[$name] = new static();
        }

        return static::$singletons[$name];
    }

    public function __construct()
    {
        $this->regex = implode('|', $this->months);
    }

    /**
     * @return string
     */
    public function getRegex()
    {
        return $this->regex;
    }

    protected function findDate($date, $direction, Closure $callback)
    {
        list($expectedYear, $inputMonth, $inputDay) = $date;

        $year = $direction * -99999;

        for ($i = $direction > 0 ? 0 : 1; $direction * $year <= $direction * $expectedYear; $i++) {
            list($year, $month, $day) = $this->getDate(
                $expectedYear - static::$baseYear + $direction * $i,
                $inputMonth,
                $inputDay
            );

            if ($year === $expectedYear) {
                $callback("$month-$day");
            }
        }
    }

    public function getHolidays($expectedYear, $inputDay, $inputMonthString, $key = null)
    {
        $inputMonth = array_search(strtolower($inputMonthString), $this->months) + 1;
        $inputDay = (int) $inputDay;

        $list = array();

        $this->findDate(array($expectedYear, $inputMonth, $inputDay), 1, function ($date) use (&$list) {
            $list[] = $date;
        });

        $this->findDate(array($expectedYear, $inputMonth, $inputDay), -1, function ($date) use (&$list) {
            array_unshift($list, $date);
        });

        $result = array_shift($list) ?: false;

        foreach ($list as $index => &$value) {
            $value = array(($key ?: 'unknown').'-oc-'.($index + 2), $value);
        }

        return array($result, $list);
    }
}
