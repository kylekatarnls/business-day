<?php

namespace Cmixin\BusinessDay;

use Cmixin\BusinessDay\Calendar\AlternativeCalendar;
use Cmixin\BusinessDay\Calendar\HijriCalendar;
use Cmixin\BusinessDay\Calendar\JewishCalendar;
use Cmixin\BusinessDay\Calendar\LunarCalendar;
use DateTime;

/**
 * @internal
 */
class HolidayCalculator
{
    /**
     * @var int
     */
    protected $year;

    /**
     * @var string
     */
    protected $outputClass;

    /**
     * @var string|null
     */
    protected $type;

    /**
     * @var array
     */
    protected $holidays;

    /**
     * @var array
     */
    protected $holidaysList;

    /**
     * @var array
     */
    protected $nextHolidays = array();

    /**
     * @var AlternativeCalendar[]
     */
    protected $calendars;

    public function __construct($year, $outputClass, $type, &$holidays, &$holidaysList)
    {
        $this->year = $year;
        $this->outputClass = $outputClass;
        $this->type = $type;
        $this->holidays = &$holidays;
        $this->holidaysList = &$holidaysList;
        $this->calendars = array(
            HijriCalendar::get(),
            JewishCalendar::get(),
        );
    }

    public function next()
    {
        if ($holiday = array_shift($this->nextHolidays)) {
            return $this->getHolidayDate($holiday[0], $holiday[1]);
        }

        $holiday = false;

        while ($holiday === false) {
            while (($key = key($this->holidays)) === 'regions') {
                next($this->holidays);
            }

            $holiday = $this->getHolidayDate($key, current($this->holidays));
        }

        return $holiday;
    }

    public function interpolateFixedDate($match)
    {
        $year = $this->year;

        switch ($match[0]) {
            case 'easter':
                static $easterDays = array();

                if (!isset($easterDays[$year])) {
                    $easterDays[$year] = easter_days($year);
                }

                $days = $easterDays[$year];

                return "$year-03-21 $days days ";

            case 'orthodox':
                return $this->getOrthodoxEasterDate('Y-m-d ');
        }
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
            $condition = trim($condition);

            if (substr($condition, 0, 4) === 'not ') {
                $expected = false;
                $condition = substr($condition, 4);
            }

            list($condition, $action) = array_pad(explode(' then ', $condition, 2), 2, null);
            $condition = strtolower($condition);
            $condition = (bool) ($condition === 'weekend'
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
        $modifiers = array(
            'before' => null,
            'after'  => null,
        );

        foreach ($modifiers as $variable => &$modifier) {
            $holiday = explode(" $variable ", $holiday, 2);

            if (count($holiday) === 2) {
                $modifier = $holiday[0];
                $holiday[0] = $holiday[1];
            }

            $holiday = $holiday[0];
        }

        return array($modifiers['before'], $modifiers['after'], $holiday);
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

    /**
     * @param DateTime $dateTime
     * @param string   $condition
     * @param bool     $substitute
     * @param string   $after
     * @param string   $before
     *
     * @return DateTime
     */
    protected function handleModifiers($dateTime, $condition, $substitute, $after, $before)
    {
        if ($condition && ($action = $this->getConditionalModifier(explode(' and ', $condition), $dateTime))) {
            $dateTime = $dateTime->modify($action);
        }

        if ($after) {
            $dateTime = $dateTime->modify($after);
        }

        if ($before) {
            $dateTime = $dateTime->modify("previous $before");
        }

        while ($substitute && ($dateTime->format('N') > 5 || isset($this->holidaysList[$dateTime->format('d/m')]))) {
            $dateTime = $dateTime->modify('+1 day');
        }

        return $dateTime;
    }

    protected function interpolateEquinox($match)
    {
        $month = strtolower($match[1]);
        $deltas = array(
            'march'     => 0,
            'spring'    => 0,
            'june'      => 8012898,
            'summer'    => 8012898,
            'september' => 16105476,
            'autumn'    => 16105476,
            'december'  => 23868894,
            'winter'    => 23868894,
        );
        $delta = isset($deltas[$month]) ? $deltas[$month] : 0;
        $sign = isset($match[2]) && $match[2] === '-' ? -1 : 1;
        $hours = isset($match[3]) ? $match[3] * 1 : 0;
        $minutes = isset($match[4]) ? $match[4] * 1 : 0;

        return date(
            'm-d',
            round(
                gmmktime(0, 0, 0, 1, 1, 2000) +
                (79.3125 + ($this->year - 2000) * 365.2425) * 86400 + $delta +
                ($hours * 60 + $minutes) * 60 * $sign
            )
        );
    }

    protected function calculateDynamicHoliday($holiday, &$dateTime = null, $key = null)
    {
        $outputClass = $this->outputClass;
        $year = $this->year;

        $holiday = str_replace(' in ', ' of ', trim(substr($holiday, 1)));

        $substitute = $this->consumeHolidayString('/\ssubstitute$/i', $holiday);

        if ($this->isIgnoredYear($holiday)) {
            return false;
        }

        list($before, $after, $holiday) = $this->extractModifiers($holiday);

        foreach ($this->calendars as $calendar) {
            if (preg_match('/^\s*(\d+)\s+('.$calendar->getRegex().')\s*$/i', $holiday, $match)) {
                list($result, $nextHolidays) = $calendar->getHolidays($this->year, $match[1], $match[2], $key);

                $this->nextHolidays = $nextHolidays;

                return $result;
            }
        }

        $holiday = preg_replace_callback('/julian\s+(\d+)-(\d+)/i', array($this, 'convertJulianDate'), trim($holiday));
        // Algorithm for Vietnamese and Korean not found, but Chinese calendar is the same 97% of the time.
        // If you can implement it, feel free to open a pull-request
        $holiday = preg_replace_callback('/(chinese|vietnamese|korean)\s+(\d+-L?\d+)/i', array($this, 'convertChineseDate'), trim($holiday));
        $holiday = preg_replace_callback('/(March|June|September|December)\s+(?:equinox|solstice)(?:\s+of\s+([+-]?)(\d+)(?::(\d+))?)?/i', array($this, 'interpolateEquinox'), trim($holiday));
        $holiday = preg_replace_callback('/(easter|orthodox)/i', array($this, 'interpolateFixedDate'), $holiday);

        $holiday = preg_replace('/\D-\d+\s*$/', '$0 days', $holiday);
        $holiday = preg_replace_callback('/^(\d{1,2})-(\d{1,2})((\s[\s\S]*)?)$/', array($this, 'padDate'), $holiday);
        $holiday = str_replace('$year', $year, $holiday);
        $holiday = preg_replace('/(\s\d+)\s*$/', '$1 days', $holiday);
        $onConditions = array();
        list($holiday, $onConditions['notOn']) = array_pad(explode(' not on ', $holiday, 2), 2, null);
        list($holiday, $onConditions['on']) = array_pad(explode(' on ', $holiday, 2), 2, null);
        list($holiday, $condition) = array_pad(explode(' if ', $holiday, 2), 2, null);

        if (strpos($holiday, "$year") === false) {
            $holiday .= " $year";
        }

        /** @var DateTime $dateTime */
        $dateTime = new $outputClass($holiday);

        $checks = array(
            'on'    => false,
            'notOn' => true,
        );

        foreach ($checks as $check => $expected) {
            if ($onConditions[$check]) {
                $days = strtolower($onConditions[$check]);
                $days = explode(',', $days === 'weekend' ? 'Saturday, Sunday' : $days);

                if (in_array(strtolower($dateTime->format('l')), array_map('trim', $days)) === $expected) {
                    return null;
                }
            }
        }

        $dateTime = $this->handleModifiers($dateTime, $condition, $substitute, $after, $before);

        return $dateTime->format('d/m');
    }

    protected function parseHoliday($holiday, &$dateTime = null, $key = null)
    {
        if (substr($holiday, 0, 1) === '=') {
            $holiday = $this->calculateDynamicHoliday($holiday, $dateTime, $key);
        }

        if ($holiday) {
            if (strpos($holiday, '-') !== false) {
                $holiday = preg_replace('/^(\d+)-(\d+)$/', '$2/$1', $holiday);
                $holiday = preg_replace('/^(\d+)-(\d+)-(\d+)$/', '$3/$2/$1', $holiday);
            }

            $this->holidaysList[$holiday] = true;
        }

        $holiday = preg_replace('/^(\d)\//', '0$1/', $holiday);
        $holiday = preg_replace('/\/(\d(\/\d+)?)$/', '/0$1', $holiday);

        return $holiday;
    }

    protected function getHolidayDate($key, $holiday)
    {
        $outputClass = $this->outputClass;
        $year = $this->year;

        if ($key === null) {
            return null;
        }

        next($this->holidays);

        if (is_callable($holiday)) {
            $holiday = call_user_func($holiday, $year);
        }

        if (is_string($holiday)) {
            $holiday = $this->parseHoliday($holiday, $dateTime, $key);
        }

        return $holiday
            ? array($key, $holiday
                ? ($this->type === 'string'
                    ? $holiday
                    : (isset($dateTime) // @codeCoverageIgnore
                        ? $dateTime
                        : $outputClass::createFromFormat('!d/m/Y', "$holiday/$year")
                    )
                )
                : $holiday,
            )
            : false;
    }
}
