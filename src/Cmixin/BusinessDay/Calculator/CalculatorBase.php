<?php

namespace Cmixin\BusinessDay\Calculator;

use Cmixin\BusinessDay\Calendar\LunarCalendar;
use DateTime;

/**
 * @internal
 */
class CalculatorBase
{
    /**
     * @var int
     */
    protected $year;

    /**
     * @var string|null
     */
    protected $type;

    /**
     * @var array
     */
    protected $holidays = [];

    public function __construct($year, $type, &$holidays)
    {
        $this->year = $year;
        $this->type = $type;
        $this->holidays = &$holidays;
    }

    public function padDate($match)
    {
        return $this->year.'-'.$this->twoDigits($match[1]).'-'.$this->twoDigits($match[2]).$match[3];
    }

    public function convertJulianDate($match)
    {
        $year = $this->year;

        do {
            $time = $this->getJulianTimestamp($year, $match[1], $match[2]);
            $delta = date('Y', $time) - $this->year;
            $year += $delta > 0 ? -1 : 1;
        } while ($delta);

        return date('m-d', $time);
    }

    public function convertChineseDate($match)
    {
        $date = new LunarCalendar($this->year.'-'.$match[2]);
        $date = $date->toGregorian();

        return $this->twoDigits($date[1]).'-'.$this->twoDigits($date[2]);
    }

    protected function twoDigits($number)
    {
        return str_pad($number, 2, '0', STR_PAD_LEFT);
    }

    protected function getJulianTimestamp($year, $month, $day)
    {
        return strtotime(jdtogregorian(juliantojd($month, $day, $year)));
    }

    protected function getOrthodoxEasterDate($format)
    {
        $year = $this->year;
        $offset = (19 * ($year % 19) + 15) % 30;
        $weekDay = (2 * ($year % 4) + 4 * ($year % 7) - $offset + 34) % 7;
        $month = floor(($offset + $weekDay + 114) / 31);
        $day = ($offset + $weekDay + 114) % 31 + 1;

        $easter = mktime(0, 0, 0, $month, $day + 13, $year);

        return date($format, $easter);
    }

    /**
     * @param array    $conditions
     * @param DateTime $dateTime
     */
    protected function getConditionalModifier($conditions, $dateTime)
    {
        foreach ($conditions as $condition) {
            $expected = true;
            $condition = preg_replace('/^\s*if\s+/', '', trim($condition));

            if (substr($condition, 0, 4) === 'not ') {
                $expected = false;
                $condition = substr($condition, 4);
            }

            [$condition, $action] = array_pad(explode(' then ', $condition, 2), 2, null);
            $condition = strtolower($condition);
            $condition = (bool) (
                $condition === 'weekend'
                ? ($dateTime->format('N') > 5)
                : in_array(strtolower($dateTime->format('l')), array_map('trim', explode(',', $condition)))
            );

            if ($condition === $expected) {
                return $action;
            }
        }
    }

    protected function extractModifiers($holiday)
    {
        $modifiers = [
            'before' => null,
            'after'  => null,
        ];

        foreach ($modifiers as $variable => &$modifier) {
            $holiday = explode(" $variable ", $holiday, 2);

            if (count($holiday) === 2) {
                $modifier = $holiday[0];
                $holiday[0] = $holiday[1];
            }

            $holiday = $holiday[0];
        }

        return [$modifiers['before'], $modifiers['after'], $holiday];
    }

    protected function consumeHolidayString($pattern, &$holiday, &$match = null)
    {
        if (preg_match($pattern, $holiday, $match)) {
            $holiday = trim(substr($holiday, 0, -strlen($match[0])));

            return true;
        }

        return false;
    }

    protected function isIgnoredYear(&$holiday)
    {
        $since = 0;
        $every = 0;

        if ($this->consumeHolidayString('/ of (even|odd|leap|non-leap) years?$/i', $holiday, $match)) {
            if (substr($match[1], -4) === 'leap') {
                return (!($this->year % 4) && ($this->year % 100 || !($this->year % 400))) !== ($match[1] === 'leap');
            }

            $since = $match[1] === 'even' ? 0 : 1;
            $every = 2;
        }

        if ($this->consumeHolidayString('/ every (\d+) years since (\d{4})$/', $holiday, $match)) {
            $since = $match[2];
            $every = $match[1];
        }

        return $every && ($delta = $this->year - $since) >= 0 && $delta % $every;
    }
}
