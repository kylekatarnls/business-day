<?php

namespace Cmixin\BusinessDay\Calendar;

/**
 * @internal
 */
abstract class AlternativeCalendar implements AlternativeCalendarInterface
{
    /**
     * @var static
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

    public function getHolidays($expectedYear, $inputDay, $inputMonthString, $key = null)
    {
        $inputMonth = array_search(strtolower($inputMonthString), $this->months) + 1;
        $inputDay = (int) $inputDay;

        $list = array();
        $year = -99999;

        for ($i = 0; $year <= $expectedYear; $i++) {
            list($year, $month, $day) = $this->getDate($expectedYear + 3761 + $i, $inputMonth, $inputDay);

            if ($year === $expectedYear) {
                $list[] = "$month-$day";
            }
        }

        $year = 99999;

        for ($i = 1; $year >= $expectedYear; $i++) {
            list($year, $month, $day) = $this->getDate($expectedYear + 3761 - $i, $inputMonth, $inputDay);

            if ($year === $expectedYear) {
                array_unshift($list, "$month-$day");
            }
        }

        $result = array_shift($list) ?: false;

        foreach ($list as $index => &$value) {
            $value = array(($key ?: 'unknown').'-oc-'.($index + 2), $value);
        }

        $this->nextHolidays = $list;

        return array($result, $list);
    }
}
