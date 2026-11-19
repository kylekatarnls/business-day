<?php

use Cmixin\BusinessDay\Calendar\LunarCalendar;

/*
 * Substitute holidays (대체공휴일, Public Holidays Act article 3): the first day after the holiday that is
 * neither a Saturday, a Sunday nor a holiday. The Korean New Year and Korean Thanksgiving days get one
 * when they fall on a Sunday or on another holiday, the other holidays listed in $coveredSince when they
 * fall on a Saturday, a Sunday or another holiday, from the year the rule first covered them.
 */
$substitutes = static function (int $year): array {
    static $cache = [];

    if (isset($cache[$year])) {
        return $cache[$year];
    }

    $lunar = static function (string $monthDay) use ($year): DateTimeImmutable {
        [$gregorianYear, $month, $day] = (new LunarCalendar("$year-$monthDay"))->toGregorian();

        return new DateTimeImmutable(sprintf('%04d-%02d-%02d', $gregorianYear, $month, $day));
    };
    $fixed = static function (string $monthDay) use ($year): DateTimeImmutable {
        return new DateTimeImmutable("$year-$monthDay");
    };

    $coveredSince = [
        'children-s-day'            => 2014,
        'liberation-day'            => 2021,
        'national-foundation-day'   => 2021,
        'hangul-day'                => 2021,
        'independence-movement-day' => 2022,
        'buddha-s-birthday'         => 2023,
        'christmas'                 => 2023,
        'labor-day'                 => 2026,
        'constitution-day'          => 2026,
    ];
    // Lunar days first, so that a substitute for a day that is both is named after the lunar holiday.
    $holidays = [];

    foreach (['korean-new-year' => $lunar('01-01'), 'korean-thanksgiving' => $lunar('8-15')] as $key => $day) {
        foreach (['-1 day', '+0 day', '+1 day'] as $shift) {
            $holidays[] = [$key, $day->modify($shift)];
        }
    }

    array_push(
        $holidays,
        [null, $fixed('01-01')],
        ['independence-movement-day', $fixed('03-01')],
        ['children-s-day', $fixed('05-05')],
        ['buddha-s-birthday', $lunar('4-8')],
        [null, $fixed('06-06')],
        ['liberation-day', $fixed('08-15')],
        ['national-foundation-day', $fixed('10-03')],
        ['hangul-day', $fixed('10-09')],
        ['christmas', $fixed('12-25')]
    );

    if ($year >= 2026) {
        $holidays[] = ['labor-day', $fixed('05-01')];
        $holidays[] = ['constitution-day', $fixed('07-17')];
    }

    $holidaysPerDate = [];
    $substituteFor = [];

    foreach ($holidays as [$key, $date]) {
        $day = $date->format('Y-m-d');
        $holidaysPerDate[$day] = ($holidaysPerDate[$day] ?? 0) + 1;

        if ($key === 'korean-new-year' || $key === 'korean-thanksgiving') {
            $substituteFor[$day] = $substituteFor[$day] ?? [$key, $date->format('N') === '7'];
        } elseif ($key !== null && $year >= ($coveredSince[$key] ?? PHP_INT_MAX)) {
            $substituteFor[$day] = $substituteFor[$day] ?? [$key, $date->format('N') >= 6];
        }
    }

    ksort($substituteFor);
    $result = [];

    foreach ($year >= 2014 ? $substituteFor : [] as $day => [$key, $fallsOnDayOff]) {
        if (!$fallsOnDayOff && $holidaysPerDate[$day] < 2) {
            continue;
        }

        $substitute = (new DateTimeImmutable($day))->modify('+1 day');

        while ($substitute->format('N') >= 6 || isset($holidaysPerDate[$substitute->format('Y-m-d')])) {
            $substitute = $substitute->modify('+1 day');
        }

        $holidaysPerDate[$substitute->format('Y-m-d')] = 1;
        $result[$key][] = $substitute->format('d/m');
    }

    return $cache[$year] = $result;
};
$substitute = static function (string $key, int $index = 0) use ($substitutes): Closure {
    return static function ($year) use ($substitutes, $key, $index) {
        return $substitutes((int) $year)[$key][$index] ?? null;
    };
};

return [
    'new-year'                              => '01-01',
    '03-01'                                 => '03-01',
    'labor-day'                             => '= 05-01 if year >= 2026',
    '05-05'                                 => '05-05',
    '06-06'                                 => '06-06',
    'constitution-day'                      => '= 07-17 if year >= 2026',
    '08-15'                                 => '08-15',
    '10-03'                                 => '10-03',
    '10-09'                                 => '10-09',
    'christmas'                             => '12-25',
    'korean-01-0-01-m1'                     => '= korean 01-01 - 1 day',
    'korean-01-0-01'                        => '= korean 01-01',
    'korean-01-0-01-p1'                     => '= korean 01-02',
    'korean-4-0-8'                          => '= korean 4-8',
    'korean-8-0-14'                         => '= korean 8-14',
    'korean-8-0-15'                         => '= korean 8-15',
    'korean-8-0-15-p1'                      => '= korean 8-16',
    'substitutes-korean-new-year'           => $substitute('korean-new-year'),
    'substitutes-independence-movement-day' => $substitute('independence-movement-day'),
    'substitutes-labor-day'                 => $substitute('labor-day'),
    'substitutes-children-s-day'            => $substitute('children-s-day'),
    'substitutes-buddha-s-birthday'         => $substitute('buddha-s-birthday'),
    'substitutes-constitution-day'          => $substitute('constitution-day'),
    'substitutes-liberation-day'            => $substitute('liberation-day'),
    'substitutes-korean-thanksgiving'       => $substitute('korean-thanksgiving'),
    'substitutes-korean-thanksgiving-2'     => $substitute('korean-thanksgiving', 1),
    'substitutes-national-foundation-day'   => $substitute('national-foundation-day'),
    'substitutes-hangul-day'                => $substitute('hangul-day'),
    'substitutes-christmas'                 => $substitute('christmas'),
];
