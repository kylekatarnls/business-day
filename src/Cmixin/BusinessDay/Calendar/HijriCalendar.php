<?php

namespace Cmixin\BusinessDay\Calendar;

class HijriCalendar extends AlternativeCalendar
{
    protected static $baseYear = 579;

    /**
     * @var array
     */
    protected $months = [
        'muharram',
        'safar',
        'rabi al-awwal',
        'rabi al-thani',
        'jumada al-awwal',
        'jumada al-thani',
        'rajab',
        'shaban',
        'ramadan',
        'shawwal',
        'dhu al-qidah',
        'dhu al-hijjah',
    ];

    public function getDate($year, $month, $day)
    {
        $date = array_map('intval', explode('/', jdtogregorian(
            floor((11 * $year + 3) / 30) + 354 * $year +
            30 * $month - floor(($month - 1) / 2) + $day + 1948440 - 385
        )));

        return [$date[2], $date[0], $date[1]];
    }
}
