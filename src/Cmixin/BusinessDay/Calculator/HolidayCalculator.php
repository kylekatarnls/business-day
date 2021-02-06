<?php

namespace Cmixin\BusinessDay\Calculator;

use Cmixin\BusinessDay\Calendar\AlternativeCalendar;
use Cmixin\BusinessDay\Calendar\HijriCalendar;
use Cmixin\BusinessDay\Calendar\JewishCalendar;
use Cmixin\BusinessDay\Util\YearCondition;
use DateTime;

/**
 * @internal
 */
class HolidayCalculator extends CalculatorBase
{
    use YearCondition;

    /**
     * @var string
     */
    protected $outputClass;

    /**
     * @var array
     */
    protected $holidaysList = [];

    /**
     * @var array
     */
    protected $nextHolidays = [];

    /**
     * @var AlternativeCalendar[]
     */
    protected $calendars;

    public function __construct($year, $type, &$holidays)
    {
        parent::__construct($year, $type, $holidays);

        $this->calendars = [ // @codeCoverageIgnore
            HijriCalendar::get(),
            JewishCalendar::get(),
        ];
    }

    /**
     * Choose the class used to mix in the business-day class.
     *
     * @param string $outputClass
     */
    public function setOutputClass(string $outputClass): void
    {
        $this->outputClass = $outputClass;
    }

    /**
     * Set the reference to the holidays list.
     *
     * @param array $holidaysList
     */
    public function setHolidaysList(array &$holidaysList): void
    {
        $this->holidaysList = &$holidaysList;
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

    public function interpolateFixedDate($match): ?string
    {
        $year = $this->year;

        switch ($match[0]) {
            case 'easter':
                static $easterDays = [];

                if (!isset($easterDays[$year])) {
                    $easterDays[$year] = easter_days($year);
                }

                $days = $easterDays[$year];

                return "$year-03-21 $days days ";

            case 'orthodox':
                return $this->getOrthodoxEasterDate('Y-m-d ');
        }

        return null;
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
        return date('m-d', static::getEquinoxTimestamp($match));
    }

    protected function getEquinoxTimestamp($match): float
    {
        $month = strtolower($match[1]);
        $deltas = [
            'march'     => 0,
            'spring'    => 0,
            'june'      => 8012898,
            'summer'    => 8012898,
            'september' => 16105476,
            'autumn'    => 16105476,
            'december'  => 23868894,
            'winter'    => 23868894,
        ];
        $delta = isset($deltas[$month]) ? $deltas[$month] : 0;
        $sign = isset($match[2]) && $match[2] === '-' ? -1 : 1;
        $hours = isset($match[3]) ? $match[3] * 1 : 0;
        $minutes = isset($match[4]) ? $match[4] * 1 : 0;

        return round(
            gmmktime(0, 0, 0, 1, 1, 2000) +
            (79.3125 + ($this->year - 2000) * 365.2425) * 86400 + $delta +
            ($hours * 60 + $minutes) * 60 * $sign
        );
    }

    protected function getKeywordReplacements(): array
    {
        $equinox = '/(March|June|September|December)\s+(?:equinox|solstice)(?:\s+of\s+([+-]?)(\d+)(?::(\d+))?)?/i';

        return [
            '/julian\s+(\d+)-(\d+)/i'                      => [$this, 'convertJulianDate'],
            // Algorithm for Vietnamese and Korean not found, but Chinese calendar is the same 97% of the time.
            // If you can implement it, feel free to open a pull-request
            '/(chinese|vietnamese|korean)\s+(\d+-L?\d+)/i' => [$this, 'convertChineseDate'],
            $equinox                                       => [$this, 'interpolateEquinox'],
            '/(easter|orthodox)/i'                         => [$this, 'interpolateFixedDate'],
            '/\D-\d+\s*$/'                                 => '$0 days',
            '/^(\d{1,2})-(\d{1,2})((\s[\s\S]*)?)$/'        => [$this, 'padDate'],
            '/(\s\d+)\s*$/'                                => '$1 days',
        ];
    }

    protected function parseKeywords(string $holiday): string
    {
        foreach ($this->getKeywordReplacements() as $regexp => $replacement) {
            $function = is_string($replacement) ? 'preg_replace' : 'preg_replace_callback';
            $holiday = $function($regexp, $replacement, trim($holiday));
        }

        return $holiday;
    }

    protected function filterConditions(array $onConditions): iterable
    {
        $checks = [
            'on'    => false,
            'notOn' => true,
        ];

        foreach ($checks as $check => $expected) {
            if ($onConditions[$check]) {
                $days = strtolower($onConditions[$check]);
                $days = $days === 'weekend' ? 'Saturday, Sunday' : $days;

                yield $days => $expected;
            }
        }
    }

    protected function parseHolidaysString(string $holiday): ?array
    {
        $outputClass = $this->outputClass;
        $year = $this->year;

        $holiday = $this->parseKeywords($holiday);
        $holiday = str_replace('$year', $year, $holiday);
        $onConditions = [];
        [$holiday, $onConditions['notOn']] = array_pad(explode(' not on ', $holiday, 2), 2, null);
        [$holiday, $onConditions['on']] = array_pad(explode(' on ', $holiday, 2), 2, null);
        [$holiday, $condition] = array_pad(explode(' if ', $holiday, 2), 2, null);

        if (strpos($holiday, "$year") === false) {
            $holiday .= " $year";
        }

        /** @var DateTime $dateTime */
        $dateTime = new $outputClass($holiday);

        foreach ($this->filterConditions($onConditions) as $days => $expected) {
            $days = array_map('trim', explode(',', $days));

            if (in_array(strtolower($dateTime->format('l')), $days) === $expected) {
                return null;
            }
        }

        return [$dateTime, $condition];
    }

    protected function calculateDynamicHoliday(string $holiday, &$dateTime = null, $key = null)
    {
        $holiday = str_replace(' in ', ' of ', trim(substr($holiday, 1)));
        $substitute = $this->consumeHolidayString('/\ssubstitute$/i', $holiday);

        if ($this->isIgnoredYear($holiday)) {
            return false;
        }

        [$before, $after, $holiday] = $this->extractModifiers($holiday);

        foreach ($this->calendars as $calendar) {
            if (preg_match('/^\s*(\d+)\s+('.$calendar->getRegex().')\s*$/i', $holiday, $match)) {
                [$result, $nextHolidays] = $calendar->getHolidays($this->year, $match[1], $match[2], $key);

                $this->nextHolidays = $nextHolidays;

                return $result;
            }
        }

        return $this->formatHolidayDefinition(
            $dateTime,
            $this->parseHolidaysString($holiday),
            $substitute,
            $after,
            $before
        );
    }

    protected function formatHolidayDefinition(&$dateTime, ?array $holidayDefinition, ...$modifiers): ?string
    {
        if ($holidayDefinition === null) {
            return null;
        }

        [$dateTime, $condition] = $holidayDefinition;

        if (!$this->matchYearCondition($dateTime, $condition)) {
            return null;
        }

        $dateTime = $this->handleModifiers($dateTime, $condition, ...$modifiers);

        return $dateTime->format('d/m');
    }

    protected function parseHoliday($holiday, &$dateTime = null, $key = null): ?string
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
        $year = $this->year;

        if ($key === null) {
            return null;
        }

        next($this->holidays);

        if (is_callable($holiday)) {
            /** @var string|null $holiday */
            $holiday = call_user_func($holiday, $year);
        }

        if (is_string($holiday)) {
            $holiday = $this->parseHoliday($holiday, $dateTime, $key);
        }

        return $holiday
            ? $this->normalizeHolidayDate($key, $holiday, $dateTime ?? null, $year)
            : false;
    }

    protected function normalizeHolidayDate($key, $holiday, $dateTime, $year)
    {
        if ($this->type !== 'string') {
            $holiday = $dateTime ?? $this->concatWithYear($holiday, $year);

            if (!$holiday) {
                return false;
            }
        }

        return [$key, $holiday];
    }

    protected function concatWithYear($holiday, $year)
    {
        $outputClass = $this->outputClass;

        [$day, $month, $holidayYear] = explode('/', "$holiday/$year");

        if ((int) $holidayYear !== (int) $year) {
            return null;
        }

        return $outputClass::createFromFormat('!Y-m-d', "$year-$month-$day");
    }
}
