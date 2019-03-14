<?php

namespace Cmixin\BusinessDay;

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

    public function __construct($year, $outputClass, $type, &$holidays, &$holidaysList)
    {
        $this->year = $year;
        $this->outputClass = $outputClass;
        $this->type = $type;
        $this->holidays = &$holidays;
        $this->holidaysList = &$holidaysList;
    }

    public function next()
    {
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
        }
    }

    public function padDate($match)
    {
        return $this->year.'-'.
            str_pad($match[1], 2, '0', STR_PAD_LEFT).'-'.
            str_pad($match[2], 2, '0', STR_PAD_LEFT).
            $match[3];
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

    protected function isIgnoredYear(&$holiday)
    {
        if (preg_match('/ of even years?$/i', $holiday)) {
            $holiday = trim(substr($holiday, 0, -14));

            if ($this->year & 1) {
                return true;
            }
        }

        if (preg_match('/every (\d+) years since (\d{4})$/', $holiday, $match)) {
            $holiday = trim(substr($holiday, 0, -strlen($match[0])));

            if (($this->year - $match[2]) % $match[1]) {
                return true;
            }
        }

        return false;
    }

    protected function calculateDynamicHoliday($holiday)
    {
        $outputClass = $this->outputClass;
        $year = $this->year;

        $holiday = str_replace(' in ', ' of ', trim(substr($holiday, 1)));

        if ($substitute = preg_match('/\ssubstitute$/i', $holiday)) {
            $holiday = trim(substr($holiday, 0, -11));
        }

        if ($this->isIgnoredYear($holiday)) {
            return false;
        }

        list($before, $after, $holiday) = $this->extractModifiers($holiday);

        $holiday = preg_replace_callback('/(easter)/i', array($this, 'interpolateFixedDate'), trim($holiday));
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

        if ($condition && ($action = $this->getConditionalModifier(explode(' and ', $condition), $dateTime))) {
            $dateTime = $dateTime->modify($action);
        }

        while ($substitute && ($dateTime->format('N') > 5 || isset($this->holidaysList[$dateTime->format('d/m')]))) {
            $dateTime = $dateTime->modify('+1 day');
        }

        if ($after) {
            $dateTime = $dateTime->modify($after);
        }

        if ($before) {
            $dateTime = $dateTime->modify("previous $before");
        }

        return $dateTime->format('d/m');
    }

    protected function parseHoliday($holiday)
    {
        if (substr($holiday, 0, 1) === '=') {
            $holiday = $this->calculateDynamicHoliday($holiday);
        }

        if ($holiday) {
            if (strpos($holiday, '-') !== false) {
                $holiday = preg_replace('/^(\d+)-(\d+)$/', '$2/$1', $holiday);
                $holiday = preg_replace('/^(\d+)-(\d+)-(\d+)$/', '$3/$2/$1', $holiday);
            }

            $this->holidaysList[$holiday] = true;
        }

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
            $holiday = $this->parseHoliday($holiday);
        }

        return $holiday
            ? array($key, $holiday
                ? ($this->type === 'string'
                    ? $holiday
                    : (isset($dateTime)
                        ? $dateTime
                        : $outputClass::createFromFormat('d/m/Y', "$holiday/$year")
                    )
                )
                : $holiday,
            )
            : false;
    }
}
