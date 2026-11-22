<?php

namespace Tests\Cmixin\Holidays;

use Cmixin\BusinessDay;
use PHPUnit\Framework\TestCase;

class KrTest extends TestCase
{
    const CARBON_CLASS = 'Carbon\Carbon';

    protected function setUp(): void
    {
        BusinessDay::enable(static::CARBON_CLASS);
        $carbon = static::CARBON_CLASS;
        $carbon::resetHolidays();
    }

    public function testHolidays()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::resetHolidays();
        $carbon::setHolidaysRegion('kr-national');

        self::assertFalse($carbon::parse('2019-02-03')->isHoliday());
        self::assertTrue($carbon::parse('2019-02-04')->isHoliday());
        self::assertTrue($carbon::parse('2019-02-05')->isHoliday());
        self::assertTrue($carbon::parse('2019-02-06')->isHoliday());
        self::assertFalse($carbon::parse('2019-02-08')->isHoliday());

        self::assertFalse($carbon::parse('2019-09-11')->isHoliday());
        self::assertTrue($carbon::parse('2019-09-12')->isHoliday());
        self::assertTrue($carbon::parse('2019-09-13')->isHoliday());
        self::assertTrue($carbon::parse('2019-09-14')->isHoliday());
        self::assertFalse($carbon::parse('2019-09-16')->isHoliday());
    }

    public function testLaborDayAndConstitutionDayFrom2026()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('kr-national');

        self::assertSame('labor-day', $carbon::parse('2026-05-01')->getHolidayId());
        self::assertSame('constitution-day', $carbon::parse('2026-07-17')->getHolidayId());
        self::assertSame('노동절', $carbon::parse('2026-05-01')->getHolidayName('ko'));
        self::assertFalse($carbon::parse('2025-05-01')->isHoliday());
        self::assertFalse($carbon::parse('2025-07-17')->isHoliday());
    }

    /**
     * @dataProvider getSubstituteHolidays
     */
    public function testSubstituteHolidays(string $date, string $holidayId)
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('kr-national');

        self::assertSame($holidayId, $carbon::parse($date)->getHolidayId());
    }

    public static function getSubstituteHolidays(): array
    {
        return [
            'Korean Thanksgiving on a Sunday (2014)'         => ['2014-09-10', 'substitutes-korean-thanksgiving'],
            'Korean Thanksgiving on Foundation Day (2017)'   => ['2017-10-06', 'substitutes-korean-thanksgiving'],
            'Children\'s Day on a Sunday (2019)'             => ['2019-05-06', 'substitutes-children-s-day'],
            'Liberation Day on a Sunday (2021)'              => ['2021-08-16', 'substitutes-liberation-day'],
            'Hangul Day on a Saturday (2021)'                => ['2021-10-11', 'substitutes-hangul-day'],
            'Korean New Year on a Sunday (2024)'             => ['2024-02-12', 'substitutes-korean-new-year'],
            'Independence Movement Day on a Saturday (2025)' => ['2025-03-03', 'substitutes-independence-movement-day'],
            'Children\'s Day on Buddha\'s Birthday (2025)'   => ['2025-05-06', 'substitutes-children-s-day'],
            'Korean Thanksgiving on a Sunday (2025)'         => ['2025-10-08', 'substitutes-korean-thanksgiving'],
            'Buddha\'s Birthday on a Sunday (2026)'          => ['2026-05-25', 'substitutes-buddha-s-birthday'],
            'Labor Day on a Saturday (2027)'                 => ['2027-05-03', 'substitutes-labor-day'],
            'Constitution Day on a Saturday (2027)'          => ['2027-07-19', 'substitutes-constitution-day'],
            'Christmas on a Saturday (2027)'                 => ['2027-12-27', 'substitutes-christmas'],
            'Korean Thanksgiving on Foundation Day (2036)'   => ['2036-10-06', 'substitutes-korean-thanksgiving'],
            'Korean Thanksgiving on a Sunday too (2036)'     => ['2036-10-07', 'substitutes-korean-thanksgiving-2'],
        ];
    }

    /**
     * @dataProvider getLunarHolidays
     */
    public function testLunarHolidaysFollowTheKoreanCalendar(string $date, string $holidayId, string $chineseDate)
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('kr-national');

        self::assertSame($holidayId, $carbon::parse($date)->getHolidayId());
        self::assertFalse($carbon::parse($chineseDate)->isHoliday());
    }

    public static function getLunarHolidays(): array
    {
        // Last column: a holiday when these lunar dates were read in the Chinese calendar, not one in Korea.
        return [
            'Buddha\'s Birthday 2012, leap 3rd month in Korea' => ['2012-05-28', 'korean-4-0-8', '2012-04-28'],
            'Buddha\'s Birthday 2023'                          => ['2023-05-27', 'korean-4-0-8', '2023-05-26'],
            'Substitute for Buddha\'s Birthday 2023'           => ['2023-05-29', 'substitutes-buddha-s-birthday', '2023-05-26'],
            'Korean New Year 2027'                             => ['2027-02-07', 'korean-01-0-01', '2027-02-05'],
            'Substitute for the Korean New Year 2027'          => ['2027-02-09', 'substitutes-korean-new-year', '2027-02-05'],
            'Day after the Korean New Year 2028'               => ['2028-01-28', 'korean-01-0-01-p1', '2028-01-25'],
            'Day after Korean Thanksgiving 2040'               => ['2040-09-22', 'korean-8-0-15-p1', '2040-09-19'],
        ];
    }

    public function testNoSubstituteBeforeTheRuleCoveredTheHoliday()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('kr-national');

        self::assertFalse($carbon::parse('2013-02-12')->isHoliday()); // Korean New Year on a Sunday before 2014
        self::assertFalse($carbon::parse('2020-03-02')->isHoliday()); // Independence Movement Day on a Sunday before 2022
        self::assertFalse($carbon::parse('2022-12-26')->isHoliday()); // Christmas on a Sunday before 2023
        self::assertFalse($carbon::parse('2021-06-07')->isHoliday()); // Memorial Day on a Sunday (never covered)
        self::assertFalse($carbon::parse('2026-09-28')->isHoliday()); // Korean Thanksgiving ending on a Saturday
    }
}
