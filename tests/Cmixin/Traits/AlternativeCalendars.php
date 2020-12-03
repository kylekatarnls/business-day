<?php

namespace Tests\Cmixin\Traits;

use Cmixin\BusinessDay;
use Cmixin\BusinessDay\Calendar\LunarCalendar;

trait AlternativeCalendars
{
    public function testWhenHijriHolidayHappensTwiceAGregorianYear()
    {
        $carbon = static::CARBON_CLASS;
        BusinessDay::enable($carbon, 'custom-hijri', [
            '27-rabi-al-thani' => '= 27 Rabi al-thani',
        ]);

        self::assertTrue($carbon::parse('2019-01-05')->isHoliday());
        self::assertTrue($carbon::parse('2019-12-25')->isHoliday());
    }

    public function testWhenJewishHolidayMissInAGregorianYear()
    {
        $carbon = static::CARBON_CLASS;
        BusinessDay::enable($carbon, 'custom-jewish', [
            '4-tevet' => '= 4 Tevet',
        ]);

        self::assertSame(1, count($carbon::getYearHolidays(2018)));
        self::assertTrue($carbon::parse('2018-12-12')->isHoliday());
        self::assertSame(0, count($carbon::getYearHolidays(2019)));
        self::assertTrue($carbon::parse('2020-01-01')->isHoliday());
        self::assertSame(2, count($carbon::getYearHolidays(2020)));
        self::assertTrue($carbon::parse('2020-12-19')->isHoliday());
    }

    public function testLunarCalendar()
    {
        $date = new LunarCalendar('2020-02-07');
        self::assertSame([2020, 2, 29], $date->toGregorian());
    }
}
