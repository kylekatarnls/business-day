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
        $coruscantHolidays = array(
            '27/12',
            '28/12',
        );
        for ($year = 1808; $year < 2500; $year += 20) {
            Carbon::resetHolidays();
            self::assertSame(array(), Carbon::getHolidays());
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
            Carbon::addHolidays('fr-national', array(
                '15/11',
                '27/12',
            ));
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
        $coruscantHolidays = array(
            '27/12',
            '28/12',
        );
        for ($year = 1808; $year < 2500; $year += 20) {
            Carbon::resetHolidays();
            Carbon::setHolidays('coruscant', $coruscantHolidays);
            self::assertSame($coruscantHolidays, Carbon::getHolidays('coruscant'));
            self::assertSame(array(), Carbon::getHolidays());
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
            Carbon::addHolidays('fr-national', array(
                '15/11',
                '27/12',
            ));
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
        if (version_compare(phpversion(), '5.5.0-dev', '<')) {
            self::markTestSkipped('Generators not available before PHP 5.5');
        }

        include __DIR__.'/generators.php';

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

    public function testIsBusinessDayStatic()
    {
        Carbon::setHolidaysRegion('fr-national');
        Carbon::setTestNow(Carbon::parse('2018-05-01 12:00:00'));
        self::assertFalse(Carbon::isBusinessDay());
        Carbon::setTestNow(Carbon::parse('2018-04-04 12:00:00'));
        self::assertTrue(Carbon::isBusinessDay());
        Carbon::setTestNow(Carbon::parse('2018-04-14 12:00:00'));
        self::assertFalse(Carbon::isBusinessDay());
        Carbon::setTestNow(Carbon::parse('2018-04-15 12:00:00'));
        self::assertFalse(Carbon::isBusinessDay());
        Carbon::setTestNow(Carbon::parse('2018-04-16 12:00:00'));
        self::assertTrue(Carbon::isBusinessDay());
        Carbon::setTestNow(Carbon::parse('2018-11-11 12:00:00'));
        self::assertFalse(Carbon::isBusinessDay());
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
        $date = Carbon::parse('2018-04-16 12:00:00');
        self::assertSame($date, $date->nextBusinessDay());
        self::assertSame('17/04/2018', $date->format('d/m/Y'));
        $date = Carbon::parse('2018-11-11 12:00:00');
        self::assertSame($date, $date->nextBusinessDay());
        self::assertSame('12/11/2018', $date->format('d/m/Y'));
    }

    public function testNextBusinessDayStatic()
    {
        Carbon::setHolidaysRegion('fr-national');
        Carbon::setTestNow(Carbon::parse('2018-05-01 12:00:00'));
        self::assertSame('02/05/2018', Carbon::nextBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-04 12:00:00'));
        self::assertSame('05/04/2018', Carbon::nextBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-14 12:00:00'));
        self::assertSame('16/04/2018', Carbon::nextBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-15 12:00:00'));
        self::assertSame('16/04/2018', Carbon::nextBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-16 12:00:00'));
        self::assertSame('17/04/2018', Carbon::nextBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-11-11 12:00:00'));
        self::assertSame('12/11/2018', Carbon::nextBusinessDay()->format('d/m/Y'));
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
        $date = Carbon::parse('2018-04-16 12:00:00');
        self::assertSame($date, $date->currentOrNextBusinessDay());
        $date = Carbon::parse('2018-11-11 12:00:00');
        self::assertSame($date, $date->currentOrNextBusinessDay());
        self::assertSame('12/11/2018', $date->format('d/m/Y'));
    }

    public function testCurrentOrNextBusinessDayStatic()
    {
        Carbon::setHolidaysRegion('fr-national');
        Carbon::setTestNow(Carbon::parse('2018-05-01 12:00:00'));
        self::assertSame('02/05/2018', Carbon::currentOrNextBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-04 12:00:00'));
        self::assertSame('04/04/2018', Carbon::currentOrNextBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-14 12:00:00'));
        self::assertSame('16/04/2018', Carbon::currentOrNextBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-15 12:00:00'));
        self::assertSame('16/04/2018', Carbon::currentOrNextBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-16 12:00:00'));
        self::assertSame('16/04/2018', Carbon::currentOrNextBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-11-11 12:00:00'));
        self::assertSame('12/11/2018', Carbon::currentOrNextBusinessDay()->format('d/m/Y'));
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
        $date = Carbon::parse('2018-04-16 12:00:00');
        self::assertSame($date, $date->previousBusinessDay());
        self::assertSame('13/04/2018', $date->format('d/m/Y'));
        $date = Carbon::parse('2018-11-11 12:00:00');
        self::assertSame($date, $date->previousBusinessDay());
        self::assertSame('09/11/2018', $date->format('d/m/Y'));
    }

    public function testPreviousBusinessDayStatic()
    {
        Carbon::setHolidaysRegion('fr-national');
        Carbon::setTestNow(Carbon::parse('2018-05-01 12:00:00'));
        self::assertSame('30/04/2018', Carbon::previousBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-04 12:00:00'));
        self::assertSame('03/04/2018', Carbon::previousBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-14 12:00:00'));
        self::assertSame('13/04/2018', Carbon::previousBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-15 12:00:00'));
        self::assertSame('13/04/2018', Carbon::previousBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-16 12:00:00'));
        self::assertSame('13/04/2018', Carbon::previousBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-11-11 12:00:00'));
        self::assertSame('09/11/2018', Carbon::previousBusinessDay()->format('d/m/Y'));
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
        $date = Carbon::parse('2018-04-16 12:00:00');
        self::assertSame($date, $date->currentOrPreviousBusinessDay());
        self::assertSame('16/04/2018', $date->format('d/m/Y'));
        $date = Carbon::parse('2018-11-11 12:00:00');
        self::assertSame($date, $date->currentOrPreviousBusinessDay());
        self::assertSame('09/11/2018', $date->format('d/m/Y'));
    }

    public function testCurrentOrPreviousBusinessDayStatic()
    {
        Carbon::setHolidaysRegion('fr-national');
        Carbon::setTestNow(Carbon::parse('2018-05-01 12:00:00'));
        self::assertSame('30/04/2018', Carbon::currentOrPreviousBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-04 12:00:00'));
        self::assertSame('04/04/2018', Carbon::currentOrPreviousBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-14 12:00:00'));
        self::assertSame('13/04/2018', Carbon::currentOrPreviousBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-15 12:00:00'));
        self::assertSame('13/04/2018', Carbon::currentOrPreviousBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-16 12:00:00'));
        self::assertSame('16/04/2018', Carbon::currentOrPreviousBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-11-11 12:00:00'));
        self::assertSame('09/11/2018', Carbon::currentOrPreviousBusinessDay()->format('d/m/Y'));
    }

    public function testAddBusinessDay()
    {
        Carbon::setHolidaysRegion('fr-national');
        self::assertSame('02/05/2018', Carbon::parse('2018-05-01 12:00:00')->addBusinessDay()->format('d/m/Y'));
        self::assertSame('05/04/2018', Carbon::parse('2018-04-04 12:00:00')->addBusinessDay()->format('d/m/Y'));
        self::assertSame('16/04/2018', Carbon::parse('2018-04-14 12:00:00')->addBusinessDay()->format('d/m/Y'));
        self::assertSame('16/04/2018', Carbon::parse('2018-04-15 12:00:00')->addBusinessDay()->format('d/m/Y'));
        self::assertSame('17/04/2018', Carbon::parse('2018-04-16 12:00:00')->addBusinessDay()->format('d/m/Y'));
        self::assertSame('12/11/2018', Carbon::parse('2018-11-11 12:00:00')->addBusinessDay()->format('d/m/Y'));
        self::assertSame('02/05/2018', Carbon::parse('2018-05-01 12:00:00')->addBusinessDays()->format('d/m/Y'));
        self::assertSame('05/04/2018', Carbon::parse('2018-04-04 12:00:00')->addBusinessDays()->format('d/m/Y'));
        self::assertSame('16/04/2018', Carbon::parse('2018-04-14 12:00:00')->addBusinessDays()->format('d/m/Y'));
        self::assertSame('16/04/2018', Carbon::parse('2018-04-15 12:00:00')->addBusinessDays()->format('d/m/Y'));
        self::assertSame('17/04/2018', Carbon::parse('2018-04-16 12:00:00')->addBusinessDays()->format('d/m/Y'));
        self::assertSame('12/11/2018', Carbon::parse('2018-11-11 12:00:00')->addBusinessDays()->format('d/m/Y'));
        self::assertSame('10/05/2018', Carbon::parse('2018-05-01 12:00:00')->addBusinessDays(6)->format('d/m/Y'));
        self::assertSame('17/04/2018', Carbon::parse('2018-04-04 12:00:00')->addBusinessDays(9)->format('d/m/Y'));
        self::assertSame('24/05/2018', Carbon::parse('2018-04-14 12:00:00')->addBusinessDays(25)->format('d/m/Y'));
        self::assertSame('18/04/2018', Carbon::parse('2018-04-15 12:00:00')->addBusinessDays(3)->format('d/m/Y'));
        self::assertSame('18/04/2018', Carbon::parse('2018-04-16 12:00:00')->addBusinessDays(2)->format('d/m/Y'));
        self::assertSame('04/12/2018', Carbon::parse('2018-11-11 12:00:00')->addBusinessDays(17)->format('d/m/Y'));
        $date = Carbon::parse('2018-11-11 12:00:00');
        self::assertSame($date, $date->addBusinessDays(17));
        self::assertSame('04/12/2018', $date->format('d/m/Y'));
    }

    public function testAddBusinessDayStatic()
    {
        Carbon::setHolidaysRegion('fr-national');
        Carbon::setTestNow(Carbon::parse('2018-05-01 12:00:00'));
        self::assertSame('02/05/2018', Carbon::addBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-04 12:00:00'));
        self::assertSame('05/04/2018', Carbon::addBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-14 12:00:00'));
        self::assertSame('16/04/2018', Carbon::addBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-15 12:00:00'));
        self::assertSame('16/04/2018', Carbon::addBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-16 12:00:00'));
        self::assertSame('17/04/2018', Carbon::addBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-11-11 12:00:00'));
        self::assertSame('12/11/2018', Carbon::addBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-05-01 12:00:00'));
        self::assertSame('02/05/2018', Carbon::addBusinessDays()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-04 12:00:00'));
        self::assertSame('05/04/2018', Carbon::addBusinessDays()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-14 12:00:00'));
        self::assertSame('16/04/2018', Carbon::addBusinessDays()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-15 12:00:00'));
        self::assertSame('16/04/2018', Carbon::addBusinessDays()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-16 12:00:00'));
        self::assertSame('17/04/2018', Carbon::addBusinessDays()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-11-11 12:00:00'));
        self::assertSame('12/11/2018', Carbon::addBusinessDays()->format('d/m/Y'));
    }

    public function testSubBusinessDay()
    {
        Carbon::setHolidaysRegion('fr-national');
        self::assertSame('30/04/2018', Carbon::parse('2018-05-01 12:00:00')->subBusinessDay()->format('d/m/Y'));
        self::assertSame('03/04/2018', Carbon::parse('2018-04-04 12:00:00')->subBusinessDay()->format('d/m/Y'));
        self::assertSame('13/04/2018', Carbon::parse('2018-04-14 12:00:00')->subBusinessDay()->format('d/m/Y'));
        self::assertSame('13/04/2018', Carbon::parse('2018-04-15 12:00:00')->subBusinessDay()->format('d/m/Y'));
        self::assertSame('13/04/2018', Carbon::parse('2018-04-16 12:00:00')->subBusinessDay()->format('d/m/Y'));
        self::assertSame('09/11/2018', Carbon::parse('2018-11-11 12:00:00')->subBusinessDay()->format('d/m/Y'));
        self::assertSame('30/04/2018', Carbon::parse('2018-05-01 12:00:00')->subBusinessDays()->format('d/m/Y'));
        self::assertSame('03/04/2018', Carbon::parse('2018-04-04 12:00:00')->subBusinessDays()->format('d/m/Y'));
        self::assertSame('13/04/2018', Carbon::parse('2018-04-14 12:00:00')->subBusinessDays()->format('d/m/Y'));
        self::assertSame('13/04/2018', Carbon::parse('2018-04-15 12:00:00')->subBusinessDays()->format('d/m/Y'));
        self::assertSame('13/04/2018', Carbon::parse('2018-04-16 12:00:00')->subBusinessDays()->format('d/m/Y'));
        self::assertSame('09/11/2018', Carbon::parse('2018-11-11 12:00:00')->subBusinessDays()->format('d/m/Y'));
        self::assertSame('23/04/2018', Carbon::parse('2018-05-01 12:00:00')->subBusinessDays(6)->format('d/m/Y'));
        self::assertSame('21/03/2018', Carbon::parse('2018-04-04 12:00:00')->subBusinessDays(9)->format('d/m/Y'));
        self::assertSame('09/03/2018', Carbon::parse('2018-04-14 12:00:00')->subBusinessDays(25)->format('d/m/Y'));
        self::assertSame('11/04/2018', Carbon::parse('2018-04-15 12:00:00')->subBusinessDays(3)->format('d/m/Y'));
        self::assertSame('12/04/2018', Carbon::parse('2018-04-16 12:00:00')->subBusinessDays(2)->format('d/m/Y'));
        self::assertSame('17/10/2018', Carbon::parse('2018-11-11 12:00:00')->subBusinessDays(17)->format('d/m/Y'));
        $date = Carbon::parse('2018-11-11 12:00:00');
        self::assertSame($date, $date->subBusinessDays(17));
        self::assertSame('17/10/2018', $date->format('d/m/Y'));
    }

    public function testSubBusinessDayStatic()
    {
        Carbon::setHolidaysRegion('fr-national');
        Carbon::setTestNow(Carbon::parse('2018-05-01 12:00:00'));
        self::assertSame('30/04/2018', Carbon::subBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-04 12:00:00'));
        self::assertSame('03/04/2018', Carbon::subBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-14 12:00:00'));
        self::assertSame('13/04/2018', Carbon::subBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-15 12:00:00'));
        self::assertSame('13/04/2018', Carbon::subBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-16 12:00:00'));
        self::assertSame('13/04/2018', Carbon::subBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-11-11 12:00:00'));
        self::assertSame('09/11/2018', Carbon::subBusinessDay()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-05-01 12:00:00'));
        self::assertSame('30/04/2018', Carbon::subBusinessDays()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-04 12:00:00'));
        self::assertSame('03/04/2018', Carbon::subBusinessDays()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-14 12:00:00'));
        self::assertSame('13/04/2018', Carbon::subBusinessDays()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-15 12:00:00'));
        self::assertSame('13/04/2018', Carbon::subBusinessDays()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-04-16 12:00:00'));
        self::assertSame('13/04/2018', Carbon::subBusinessDays()->format('d/m/Y'));
        Carbon::setTestNow(Carbon::parse('2018-11-11 12:00:00'));
        self::assertSame('09/11/2018', Carbon::subBusinessDays()->format('d/m/Y'));
    }
}
