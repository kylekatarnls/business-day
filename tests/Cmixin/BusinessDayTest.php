<?php

namespace Cmixin\Tests;

use Carbon\Carbon;
use Cmixin\BusinessDay;
use PHPUnit\Framework\TestCase;

class BusinessDayTest extends TestCase
{
    protected function setUp()
    {
        BusinessDay::enable('Carbon\Carbon');
        Carbon::resetHolidays();
    }

    public function testEnable()
    {
        self::assertTrue(Carbon::enable());
    }

    public function testUsHolidays()
    {
        Carbon::setHolidaysRegion('us-national');
        self::assertTrue(Carbon::parse('2018-01-01 00:00:00')->isHoliday());
        self::assertTrue(Carbon::parse('2018-01-15 00:00:00')->isHoliday());
        self::assertTrue(Carbon::parse('2018-05-28 00:00:00')->isHoliday());
        self::assertTrue(Carbon::parse('2018-07-04 00:00:00')->isHoliday());
        self::assertTrue(Carbon::parse('2018-09-03 00:00:00')->isHoliday());
        self::assertTrue(Carbon::parse('2018-11-22 00:00:00')->isHoliday());
        self::assertTrue(Carbon::parse('2018-12-25 00:00:00')->isHoliday());
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
                $closure = function ($year) use ($i) {
                    $c = $year % 10;

                    return "0$c/0$i";
                };

                yield $closure;
            }
        }));
        Carbon::setHolidays('test', call_user_func(function () {
            for ($i = 2; $i < 4; $i++) {
                $closure = function ($year) use ($i) {
                    $c = $year % 10;

                    return "0$c/0$i";
                };

                yield $closure;
            }
        }));
        Carbon::addHolidays('test', call_user_func(function () {
            for ($i = 6; $i < 10; $i++) {
                $closure = function ($year) use ($i) {
                    $c = $year % 10;

                    return "0$c/0$i";
                };

                yield $closure;
            }
        }));
        Carbon::setHolidaysRegion('test');
        self::assertTrue(Carbon::parse('2018-02-08 03:30:40')->isHoliday());
        self::assertFalse(Carbon::parse('2018-01-08 03:30:40')->isHoliday());
        self::assertFalse(Carbon::parse('2016-02-08 03:30:40')->isHoliday());
        self::assertTrue(Carbon::parse('2023-03-03 03:30:40')->isHoliday());
        self::assertFalse(Carbon::parse('2023-05-03 03:30:40')->isHoliday());
        self::assertTrue(Carbon::parse('2023-06-03 03:30:40')->isHoliday());
    }

    public function testIsBusinessDay()
    {
        Carbon::setHolidaysRegion('fr-national');
        self::assertFalse(Carbon::parse('2018-05-01 12:00:00')->isBusinessDay());
        self::assertTrue(Carbon::parse('2018-04-04 12:00:00')->isBusinessDay());
        self::assertFalse(Carbon::parse('2018-04-14 12:00:00')->isBusinessDay());
        self::assertFalse(Carbon::parse('2018-04-15 12:00:00')->isBusinessDay());
        self::assertTrue(Carbon::parse('2018-04-16 12:00:00')->isBusinessDay());
        self::assertFalse(Carbon::parse('2018-11-11 12:00:00')->isBusinessDay());
    }

    public function testNextBusinessDay()
    {
        Carbon::setHolidaysRegion('fr-national');
        self::assertSame('02/05/2018', Carbon::parse('2018-05-01 12:00:00')->nextBusinessDay()->format('d/m/Y'));
        self::assertSame('05/04/2018', Carbon::parse('2018-04-04 12:00:00')->nextBusinessDay()->format('d/m/Y'));
        self::assertSame('16/04/2018', Carbon::parse('2018-04-14 12:00:00')->nextBusinessDay()->format('d/m/Y'));
        self::assertSame('16/04/2018', Carbon::parse('2018-04-15 12:00:00')->nextBusinessDay()->format('d/m/Y'));
        self::assertSame('17/04/2018', Carbon::parse('2018-04-16 12:00:00')->nextBusinessDay()->format('d/m/Y'));
        self::assertSame('12/11/2018', Carbon::parse('2018-11-11 12:00:00')->nextBusinessDay()->format('d/m/Y'));
    }

    public function testCurrentOrNextBusinessDay()
    {
        Carbon::setHolidaysRegion('fr-national');
        self::assertSame('02/05/2018', Carbon::parse('2018-05-01 12:00:00')->currentOrNextBusinessDay()->format('d/m/Y'));
        self::assertSame('04/04/2018', Carbon::parse('2018-04-04 12:00:00')->currentOrNextBusinessDay()->format('d/m/Y'));
        self::assertSame('16/04/2018', Carbon::parse('2018-04-14 12:00:00')->currentOrNextBusinessDay()->format('d/m/Y'));
        self::assertSame('16/04/2018', Carbon::parse('2018-04-15 12:00:00')->currentOrNextBusinessDay()->format('d/m/Y'));
        self::assertSame('16/04/2018', Carbon::parse('2018-04-16 12:00:00')->currentOrNextBusinessDay()->format('d/m/Y'));
        self::assertSame('12/11/2018', Carbon::parse('2018-11-11 12:00:00')->currentOrNextBusinessDay()->format('d/m/Y'));
    }

    public function testPreviousBusinessDay()
    {
        Carbon::setHolidaysRegion('fr-national');
        self::assertSame('30/04/2018', Carbon::parse('2018-05-01 12:00:00')->previousBusinessDay()->format('d/m/Y'));
        self::assertSame('03/04/2018', Carbon::parse('2018-04-04 12:00:00')->previousBusinessDay()->format('d/m/Y'));
        self::assertSame('13/04/2018', Carbon::parse('2018-04-14 12:00:00')->previousBusinessDay()->format('d/m/Y'));
        self::assertSame('13/04/2018', Carbon::parse('2018-04-15 12:00:00')->previousBusinessDay()->format('d/m/Y'));
        self::assertSame('13/04/2018', Carbon::parse('2018-04-16 12:00:00')->previousBusinessDay()->format('d/m/Y'));
        self::assertSame('09/11/2018', Carbon::parse('2018-11-11 12:00:00')->previousBusinessDay()->format('d/m/Y'));
    }

    public function testCurrentOrPreviousBusinessDay()
    {
        Carbon::setHolidaysRegion('fr-national');
        self::assertSame('30/04/2018', Carbon::parse('2018-05-01 12:00:00')->currentOrPreviousBusinessDay()->format('d/m/Y'));
        self::assertSame('04/04/2018', Carbon::parse('2018-04-04 12:00:00')->currentOrPreviousBusinessDay()->format('d/m/Y'));
        self::assertSame('13/04/2018', Carbon::parse('2018-04-14 12:00:00')->currentOrPreviousBusinessDay()->format('d/m/Y'));
        self::assertSame('13/04/2018', Carbon::parse('2018-04-15 12:00:00')->currentOrPreviousBusinessDay()->format('d/m/Y'));
        self::assertSame('16/04/2018', Carbon::parse('2018-04-16 12:00:00')->currentOrPreviousBusinessDay()->format('d/m/Y'));
        self::assertSame('09/11/2018', Carbon::parse('2018-11-11 12:00:00')->currentOrPreviousBusinessDay()->format('d/m/Y'));
    }
}
