<?php

namespace Tests\Cmixin\Calendar;

use Cmixin\BusinessDay\Calendar\KoreanLunarCalendar;
use Cmixin\BusinessDay\Calendar\LunarCalendar;
use PHPUnit\Framework\TestCase;

class KoreanLunarCalendarTest extends TestCase
{
    /** @dataProvider getDates */
    public function testDatesPublishedByKasi(string $lunar, string $gregorian): void
    {
        self::assertSame($gregorian, implode('-', (new KoreanLunarCalendar($lunar))->toGregorian()));
        self::assertSame($gregorian, implode('-', KoreanLunarCalendar::convertLunarToGregorian($lunar)));
    }

    public static function getDates(): array
    {
        $tests = [
            ['1888-01-01', '1888-2-12'], // first year of the table
            ['1988-01-01', '1988-2-18'], // 1988-2-17 in China
            ['2012-L3-1', '2012-4-21'], // leap 3rd month in Korea, leap 4th month in China
            ['2012-4-8', '2012-5-28'], // 2012-4-28 in China
            ['2017-02-01', '2017-2-26'], // new moon at 23:58, Korean time
            ['2017-L5-1', '2017-6-24'], // leap 5th month in Korea, leap 6th month in China
            ['2023-4-8', '2023-5-27'], // 2023-5-26 in China
            ['2025-L6-1', '2025-7-25'], // same leap month in both calendars
            ['2027-01-01', '2027-2-7'], // 2027-2-6 in China
            ['2028-01-01', '2028-1-27'], // 2028-1-26 in China
            ['2040-8-15', '2040-9-21'], // 2040-9-20 in China
            ['2050-11-18', '2050-12-31'], // last day KASI publishes
        ];

        return array_combine(array_column($tests, 0), $tests);
    }

    public function testChineseCalendarKeepsItsOwnDates(): void
    {
        self::assertSame('2012-4-28', implode('-', (new LunarCalendar('2012-4-8'))->toGregorian()));
        self::assertSame('2027-2-6', implode('-', (new LunarCalendar('2027-01-01'))->toGregorian()));
        self::assertSame('2027-2-6', implode('-', LunarCalendar::convertLunarToGregorian('2027-01-01')));
    }

    public function testYearsKasiDoesNotPublishFallBackToTheChineseCalendar(): void
    {
        self::assertSame(
            (new LunarCalendar('2051-01-01'))->toGregorian(),
            (new KoreanLunarCalendar('2051-01-01'))->toGregorian()
        );
        self::assertSame('2051-2-11', implode('-', (new KoreanLunarCalendar('2051-01-01'))->toGregorian()));
    }
}
