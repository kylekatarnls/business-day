<?php

namespace Cmixin\Tests;

use Carbon\Carbon;
use Cmixin\BusinessDay;
use PHPUnit\Framework\TestCase;

class BusinessDayTest extends TestCase
{
    protected function setUp()
    {
        BusinessDay::enable(Carbon::class);
        Carbon::resetHolidays();
    }

    public function testEnable()
    {
        self::assertTrue(Carbon::enable());
    }

    public function testIsHoliday()
    {
        $coruscantHolidays = [
            '27/12',
            '28/12',
        ];
        for ($year = 1808; $year < 2500; $year += 20) {
            Carbon::resetHolidays();
            self::assertSame([], Carbon::getHolidays());
            Carbon::setHolidays('coruscant', $coruscantHolidays);
            self::assertFalse(Carbon::parse("$year-12-25 03:30:40")->isHoliday());
            self::assertFalse(Carbon::parse("$year-12-26 03:30:40")->isHoliday());
            self::assertFalse(Carbon::parse("$year-12-27 03:30:40")->isHoliday());
            Carbon::setHolidaysRegion('us-national');
            self::assertTrue(Carbon::parse("$year-12-25 03:30:40")->isHoliday());
            self::assertFalse(Carbon::parse("$year-12-26 03:30:40")->isHoliday());
            self::assertFalse(Carbon::parse("$year-12-27 03:30:40")->isHoliday());
            Carbon::setHolidaysRegion('fr-east');
            self::assertTrue(Carbon::parse("$year-12-25 03:30:40")->isHoliday());
            self::assertTrue(Carbon::parse("$year-12-26 03:30:40")->isHoliday());
            self::assertFalse(Carbon::parse("$year-12-27 03:30:40")->isHoliday());
            Carbon::setHolidaysRegion('fr-national');
            self::assertTrue(Carbon::parse("$year-12-25 03:30:40")->isHoliday());
            self::assertFalse(Carbon::parse("$year-12-26 03:30:40")->isHoliday());
            self::assertFalse(Carbon::parse("$year-12-27 03:30:40")->isHoliday());
            Carbon::addHolidays('fr-national', [
                '15/11',
                '27/12',
            ]);
            self::assertTrue(Carbon::parse("$year-12-25 03:30:40")->isHoliday());
            self::assertFalse(Carbon::parse("$year-12-26 03:30:40")->isHoliday());
            self::assertTrue(Carbon::parse("$year-12-27 03:30:40")->isHoliday());
            Carbon::setHolidaysRegion('coruscant');
            self::assertFalse(Carbon::parse("$year-12-25 03:30:40")->isHoliday());
            self::assertFalse(Carbon::parse("$year-12-26 03:30:40")->isHoliday());
            self::assertTrue(Carbon::parse("$year-12-27 03:30:40")->isHoliday());
            self::assertSame($coruscantHolidays, Carbon::getHolidays());
        }
    }

    public function testIsHolidayStatic()
    {
        $coruscantHolidays = [
            '27/12',
            '28/12',
        ];
        for ($year = 1808; $year < 2500; $year += 20) {
            Carbon::resetHolidays();
            self::assertSame([], Carbon::getHolidays());
            Carbon::setHolidays('coruscant', $coruscantHolidays);
            Carbon::setTestNow(Carbon::parse("$year-12-25 03:30:40"));
            self::assertFalse(Carbon::isHoliday());
            Carbon::setTestNow(Carbon::parse("$year-12-26 03:30:40"));
            self::assertFalse(Carbon::isHoliday());
            Carbon::setTestNow(Carbon::parse("$year-12-27 03:30:40"));
            self::assertFalse(Carbon::isHoliday());
            Carbon::setHolidaysRegion('us-national');
            Carbon::setTestNow(Carbon::parse("$year-12-25 03:30:40"));
            self::assertTrue(Carbon::isHoliday());
            Carbon::setTestNow(Carbon::parse("$year-12-26 03:30:40"));
            self::assertFalse(Carbon::isHoliday());
            Carbon::setTestNow(Carbon::parse("$year-12-27 03:30:40"));
            self::assertFalse(Carbon::isHoliday());
            Carbon::setHolidaysRegion('fr-east');
            Carbon::setTestNow(Carbon::parse("$year-12-25 03:30:40"));
            self::assertTrue(Carbon::isHoliday());
            Carbon::setTestNow(Carbon::parse("$year-12-26 03:30:40"));
            self::assertTrue(Carbon::isHoliday());
            Carbon::setTestNow(Carbon::parse("$year-12-27 03:30:40"));
            self::assertFalse(Carbon::isHoliday());
            Carbon::setHolidaysRegion('fr-national');
            Carbon::setTestNow(Carbon::parse("$year-12-25 03:30:40"));
            self::assertTrue(Carbon::isHoliday());
            Carbon::setTestNow(Carbon::parse("$year-12-26 03:30:40"));
            self::assertFalse(Carbon::isHoliday());
            Carbon::setTestNow(Carbon::parse("$year-12-27 03:30:40"));
            self::assertFalse(Carbon::isHoliday());
            Carbon::addHolidays('fr-national', [
                '15/11',
                '27/12',
            ]);
            Carbon::setTestNow(Carbon::parse("$year-12-25 03:30:40"));
            self::assertTrue(Carbon::isHoliday());
            Carbon::setTestNow(Carbon::parse("$year-12-26 03:30:40"));
            self::assertFalse(Carbon::isHoliday());
            Carbon::setTestNow(Carbon::parse("$year-12-27 03:30:40"));
            self::assertTrue(Carbon::isHoliday());
            Carbon::setHolidaysRegion('coruscant');
            Carbon::setTestNow(Carbon::parse("$year-12-25 03:30:40"));
            self::assertFalse(Carbon::isHoliday());
            Carbon::setTestNow(Carbon::parse("$year-12-26 03:30:40"));
            self::assertFalse(Carbon::isHoliday());
            Carbon::setTestNow(Carbon::parse("$year-12-27 03:30:40"));
            self::assertTrue(Carbon::isHoliday());
            self::assertSame($coruscantHolidays, Carbon::getHolidays());
        }
    }

    public function testAddHolidaysTraversable()
    {
        Carbon::addHolidays('test', call_user_func(function () {
            for ($i = 1; $i < 4; $i++) {
                yield function ($year) use ($i) {
                    $c = $year % 10;

                    return "0$c/0$i";
                };
            }
        }));
        Carbon::setHolidays('test', call_user_func(function () {
            for ($i = 2; $i < 4; $i++) {
                yield function ($year) use ($i) {
                    $c = $year % 10;

                    return "0$c/0$i";
                };
            }
        }));
        Carbon::addHolidays('test', call_user_func(function () {
            for ($i = 6; $i < 10; $i++) {
                yield function ($year) use ($i) {
                    $c = $year % 10;

                    return "0$c/0$i";
                };
            }
        }));
        Carbon::setHolidaysRegion('test');
        self::assertTrue(Carbon::parse("2018-02-08 03:30:40")->isHoliday());
        self::assertFalse(Carbon::parse("2018-01-08 03:30:40")->isHoliday());
        self::assertFalse(Carbon::parse("2016-02-08 03:30:40")->isHoliday());
        self::assertTrue(Carbon::parse("2023-03-03 03:30:40")->isHoliday());
        self::assertFalse(Carbon::parse("2023-05-03 03:30:40")->isHoliday());
        self::assertTrue(Carbon::parse("2023-06-03 03:30:40")->isHoliday());
    }
}
