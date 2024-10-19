<?php

namespace Tests\Cmixin\Calendar;

use Cmixin\BusinessDay\Calendar\HijriCalendar;
use PHPUnit\Framework\TestCase;

class HijriCalendarTest extends TestCase
{
    /** @dataProvider getDates */
    public function testHolidaysSpecificDates(string $hijri, string $gregorian): void
    {
        $calendar = new HijriCalendar();
        [$year, $month, $day] = explode('-', $hijri);
        $date = $calendar->getDate($year, $month, $day);

        self::assertSame($gregorian, implode('-', $date));
    }

    public static function getDates(): array
    {
        $tests = [
            ['1428-12-10', '2007-12-20'],
            ['1429-12-10', '2008-12-9'], // some converters give 2008-12-8
            ['1430-12-10', '2009-11-28'], // some converters give 2009-11-27
        ];

        return array_combine(array_column($tests, 0), $tests);
    }
}
