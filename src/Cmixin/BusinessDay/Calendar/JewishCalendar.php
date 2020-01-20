<?php

namespace Cmixin\BusinessDay\Calendar;

class JewishCalendar extends AlternativeCalendar
{
    protected static $baseYear = -3761;

    /**
     * @var array
     */
    protected $months = [
        'tishrei',
        'cheshvan',
        'kislev',
        'tevet',
        'shvat',
        'adar',
        'adar ii',
        'nisan',
        'iyyar',
        'sivan',
        'tamuz',
        'av',
        'elul',
    ];

    public function getDate($year, $month, $day)
    {
        $date = array_map('intval', explode('/', jdtogregorian(jewishtojd($month, $day, $year))));

        return [$date[2], $date[0], $date[1]];
    }
}
