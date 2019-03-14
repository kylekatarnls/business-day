<?php

namespace Cmixin\BusinessDay;

use DateTime;

/**
 * Class HolidayCalculator
 *
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

    protected function calculateDynamicHoliday($holiday)
    {
        $outputClass = $this->outputClass;
        $year = $this->year;

        $holiday = str_replace(' in ', ' of ', trim(substr($holiday, 1)));

        if ($substitute = preg_match('/\ssubstitute$/i', $holiday)) {
            $holiday = trim(substr($holiday, 0, -11));
        }

        if (preg_match('/ of even years?$/i', $holiday)) {
            $holiday = trim(substr($holiday, 0, -14));

            if ($year & 1) {
                return false;
            }
        }

        if (preg_match('/every (\d+) years since (\d{4})$/', $holiday, $match)) {
            $holiday = trim(substr($holiday, 0, -strlen($match[0])));

            if (($year - $match[2]) % $match[1]) {
                return false;
            }
        }

        $before = null;
        $after = null;

        foreach (array('before', 'after') as $variable) {
            $holiday = explode(" $variable ", $holiday, 2);

            if (count($holiday) === 2) {
                $$variable = $holiday[0];
                $holiday[0] = $holiday[1];
            }

            $holiday = $holiday[0];
        }

        $holiday = preg_replace_callback('/(easter)/i', function ($match) use ($year) {
            switch ($match[0]) {
                case 'easter':
                    static $easterDays = array();

                    if (!isset($easterDays[$year])) {
                        $easterDays[$year] = easter_days($year);
                    }

                    $days = $easterDays[$year];

                    return "$year-03-21 $days days ";
            }
        }, trim($holiday));
        $holiday = preg_replace_callback('/^(\d{1,2})-(\d{1,2})((\s[\s\S]*)?)$/', function ($match) use ($year) {
            return "$year-".
                str_pad($match[1], 2, '0', STR_PAD_LEFT).'-'.
                str_pad($match[2], 2, '0', STR_PAD_LEFT).
                $match[3];
        }, $holiday);
        $holiday = str_replace('$year', $year, $holiday);
        $holiday = preg_replace('/(\s\d+)\s*$/', '$1 days', $holiday);
        list($holiday, $notOn) = array_pad(explode(' not on ', $holiday, 2), 2, null);
        list($holiday, $on) = array_pad(explode(' on ', $holiday, 2), 2, null);
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
            if ($$check) {
                $days = strtolower($$check);
                $days = $days === 'weekend' ? 'Saturday, Sunday' : $days;

                if (in_array(strtolower($dateTime->format('l')), array_map('trim', explode(',', $days))) === $expected) {
                    return null;
                }
            }
        }

        if ($condition) {
            $expected = true;

            $conditions = explode(' and ', $condition);

            foreach ($conditions as $condition) {
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
                    $dateTime = $dateTime->modify($action);

                    break;
                }
            }
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
            ? array($key, $holiday ? ($this->type === 'string' ? $holiday : (isset($dateTime) ? $dateTime : $outputClass::createFromFormat('d/m/Y', "$holiday/$year"))) : $holiday)
            : false;
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
}
