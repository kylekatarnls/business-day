<?php

namespace Tests\Cmixin;

use Cmixin\BusinessDay;
use PHPUnit\Framework\TestCase;

class BusinessDayTest extends TestCase
{
    const CARBON_CLASS = 'Carbon\Carbon';

    protected function setUp()
    {
        BusinessDay::enable(static::CARBON_CLASS);
        $carbon = static::CARBON_CLASS;
        $carbon::resetHolidays();
    }

    public function testEnable()
    {
        $carbon = static::CARBON_CLASS;
        self::assertTrue($carbon::enable());
    }

    public function testUsHolidays()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('us-national');
        self::assertTrue($carbon::parse('2018-01-01 00:00:00')->isHoliday());
        self::assertTrue($carbon::parse('2018-01-15 00:00:00')->isHoliday());
        self::assertTrue($carbon::parse('2018-05-28 00:00:00')->isHoliday());
        self::assertTrue($carbon::parse('2018-07-04 00:00:00')->isHoliday());
        self::assertTrue($carbon::parse('2018-09-03 00:00:00')->isHoliday());
        self::assertTrue($carbon::parse('2018-11-22 00:00:00')->isHoliday());
        self::assertTrue($carbon::parse('2018-12-25 00:00:00')->isHoliday());
    }

    public function testIsHoliday()
    {
        $carbon = static::CARBON_CLASS;
        $coruscantHolidays = array(
            '27/12',
            '28/12',
        );
        for ($year = 1808; $year < 2500; $year += 20) {
            $carbon::resetHolidays();
            self::assertSame(array(), $carbon::getHolidays());
            $carbon::setHolidays('coruscant', $coruscantHolidays);
            self::assertFalse($carbon::parse("$year-12-25 03:30:40")->isHoliday());
            self::assertFalse($carbon::parse("$year-12-26 03:30:40")->isHoliday());
            self::assertFalse($carbon::parse("$year-12-27 03:30:40")->isHoliday());
            $carbon::setHolidaysRegion('us-national');
            self::assertTrue($carbon::parse("$year-12-25 03:30:40")->isHoliday());
            self::assertFalse($carbon::parse("$year-12-26 03:30:40")->isHoliday());
            self::assertFalse($carbon::parse("$year-12-27 03:30:40")->isHoliday());
            $carbon::setHolidaysRegion('fr-east');
            self::assertTrue($carbon::parse("$year-12-25 03:30:40")->isHoliday());
            self::assertTrue($carbon::parse("$year-12-26 03:30:40")->isHoliday());
            self::assertFalse($carbon::parse("$year-12-27 03:30:40")->isHoliday());
            $carbon::setHolidaysRegion('fr-national');
            self::assertTrue($carbon::parse("$year-12-25 03:30:40")->isHoliday());
            self::assertFalse($carbon::parse("$year-12-26 03:30:40")->isHoliday());
            self::assertFalse($carbon::parse("$year-12-27 03:30:40")->isHoliday());
            $carbon::addHolidays('fr-national', array(
                '15/11',
                '27/12',
            ));
            self::assertTrue($carbon::parse("$year-12-25 03:30:40")->isHoliday());
            self::assertFalse($carbon::parse("$year-12-26 03:30:40")->isHoliday());
            self::assertTrue($carbon::parse("$year-12-27 03:30:40")->isHoliday());
            $carbon::setHolidaysRegion('coruscant');
            self::assertFalse($carbon::parse("$year-12-25 03:30:40")->isHoliday());
            self::assertFalse($carbon::parse("$year-12-26 03:30:40")->isHoliday());
            self::assertTrue($carbon::parse("$year-12-27 03:30:40")->isHoliday());
            self::assertSame($coruscantHolidays, $carbon::getHolidays());

            self::assertTrue($carbon::initializeHolidaysRegion());
        }
    }

    public function testIsHolidayStatic()
    {
        $carbon = static::CARBON_CLASS;
        $coruscantHolidays = array(
            '27/12',
            '28/12',
        );
        for ($year = 1808; $year < 2500; $year += 20) {
            $carbon::resetHolidays();
            $carbon::setHolidays('coruscant', $coruscantHolidays);
            self::assertSame($coruscantHolidays, $carbon::getHolidays('coruscant'));
            self::assertSame(array(), $carbon::getHolidays());
            $carbon::setTestNow($carbon::parse("$year-12-25 03:30:40"));
            self::assertFalse($carbon::isHoliday());
            $carbon::setTestNow($carbon::parse("$year-12-26 03:30:40"));
            self::assertFalse($carbon::isHoliday());
            $carbon::setTestNow($carbon::parse("$year-12-27 03:30:40"));
            self::assertFalse($carbon::isHoliday());
            $carbon::setHolidaysRegion('us-national');
            $carbon::setTestNow($carbon::parse("$year-12-25 03:30:40"));
            self::assertTrue($carbon::isHoliday());
            $carbon::setTestNow($carbon::parse("$year-12-26 03:30:40"));
            self::assertFalse($carbon::isHoliday());
            $carbon::setTestNow($carbon::parse("$year-12-27 03:30:40"));
            self::assertFalse($carbon::isHoliday());
            $carbon::setHolidaysRegion('fr-east');
            $carbon::setTestNow($carbon::parse("$year-12-25 03:30:40"));
            self::assertTrue($carbon::isHoliday());
            $carbon::setTestNow($carbon::parse("$year-12-26 03:30:40"));
            self::assertTrue($carbon::isHoliday());
            $carbon::setTestNow($carbon::parse("$year-12-27 03:30:40"));
            self::assertFalse($carbon::isHoliday());
            $carbon::setHolidaysRegion('fr-national');
            $carbon::setTestNow($carbon::parse("$year-12-25 03:30:40"));
            self::assertTrue($carbon::isHoliday());
            $carbon::setTestNow($carbon::parse("$year-12-26 03:30:40"));
            self::assertFalse($carbon::isHoliday());
            $carbon::setTestNow($carbon::parse("$year-12-27 03:30:40"));
            self::assertFalse($carbon::isHoliday());
            $carbon::addHolidays('fr-national', array(
                '15/11',
                '27/12',
            ));
            $carbon::setTestNow($carbon::parse("$year-12-25 03:30:40"));
            self::assertTrue($carbon::isHoliday());
            $carbon::setTestNow($carbon::parse("$year-12-26 03:30:40"));
            self::assertFalse($carbon::isHoliday());
            $carbon::setTestNow($carbon::parse("$year-12-27 03:30:40"));
            self::assertTrue($carbon::isHoliday());
            $carbon::setHolidaysRegion('coruscant');
            $carbon::setTestNow($carbon::parse("$year-12-25 03:30:40"));
            self::assertFalse($carbon::isHoliday());
            $carbon::setTestNow($carbon::parse("$year-12-26 03:30:40"));
            self::assertFalse($carbon::isHoliday());
            $carbon::setTestNow($carbon::parse("$year-12-27 03:30:40"));
            self::assertTrue($carbon::isHoliday());
            self::assertSame($coruscantHolidays, $carbon::getHolidays());
        }
    }

    public function testAddHolidaysTraversable()
    {
        if (version_compare(phpversion(), '5.5.0-dev', '<')) {
            self::markTestSkipped('Generators not available before PHP 5.5');
        }

        $carbon = static::CARBON_CLASS;
        $generators = new TestGenerators();
        $generators->run($carbon);
        $carbon::setHolidaysRegion('test');
        $date = $carbon::parse('2018-02-08 03:30:40');
        self::assertTrue($carbon::parse('2018-02-08 03:30:40')->isHoliday());
        self::assertFalse($carbon::parse('2018-01-08 03:30:40')->isHoliday());
        self::assertFalse($carbon::parse('2016-02-08 03:30:40')->isHoliday());
        self::assertTrue($carbon::parse('2023-03-03 03:30:40')->isHoliday());
        self::assertFalse($carbon::parse('2023-05-03 03:30:40')->isHoliday());
        self::assertTrue($carbon::parse('2023-06-03 03:30:40')->isHoliday());
    }

    public function testIsBusinessDay()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('fr-national');
        self::assertFalse($carbon::parse('2018-05-01 12:00:00')->isBusinessDay());
        self::assertTrue($carbon::parse('2018-04-04 12:00:00')->isBusinessDay());
        self::assertFalse($carbon::parse('2018-04-14 12:00:00')->isBusinessDay());
        self::assertFalse($carbon::parse('2018-04-15 12:00:00')->isBusinessDay());
        self::assertTrue($carbon::parse('2018-04-16 12:00:00')->isBusinessDay());
        self::assertFalse($carbon::parse('2018-11-11 12:00:00')->isBusinessDay());
    }

    public function testIsBusinessDayStatic()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('fr-national');
        $carbon::setTestNow($carbon::parse('2018-05-01 12:00:00'));
        self::assertFalse($carbon::isBusinessDay());
        $carbon::setTestNow($carbon::parse('2018-04-04 12:00:00'));
        self::assertTrue($carbon::isBusinessDay());
        $carbon::setTestNow($carbon::parse('2018-04-14 12:00:00'));
        self::assertFalse($carbon::isBusinessDay());
        $carbon::setTestNow($carbon::parse('2018-04-15 12:00:00'));
        self::assertFalse($carbon::isBusinessDay());
        $carbon::setTestNow($carbon::parse('2018-04-16 12:00:00'));
        self::assertTrue($carbon::isBusinessDay());
        $carbon::setTestNow($carbon::parse('2018-11-11 12:00:00'));
        self::assertFalse($carbon::isBusinessDay());
    }

    public function testNextBusinessDay()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('fr-national');
        self::assertSame('02/05/2018', $carbon::parse('2018-05-01 12:00:00')->nextBusinessDay()->format('d/m/Y'));
        self::assertSame('05/04/2018', $carbon::parse('2018-04-04 12:00:00')->nextBusinessDay()->format('d/m/Y'));
        self::assertSame('16/04/2018', $carbon::parse('2018-04-14 12:00:00')->nextBusinessDay()->format('d/m/Y'));
        self::assertSame('16/04/2018', $carbon::parse('2018-04-15 12:00:00')->nextBusinessDay()->format('d/m/Y'));
        self::assertSame('17/04/2018', $carbon::parse('2018-04-16 12:00:00')->nextBusinessDay()->format('d/m/Y'));
        self::assertSame('12/11/2018', $carbon::parse('2018-11-11 12:00:00')->nextBusinessDay()->format('d/m/Y'));
        $date = $carbon::parse('2018-04-16 12:00:00');
        self::assertSame($date, $date->nextBusinessDay());
        self::assertSame('17/04/2018', $date->format('d/m/Y'));
        $date = $carbon::parse('2018-11-11 12:00:00');
        self::assertSame($date, $date->nextBusinessDay());
        self::assertSame('12/11/2018', $date->format('d/m/Y'));
    }

    public function testNextBusinessDayStatic()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('fr-national');
        $carbon::setTestNow($carbon::parse('2018-05-01 12:00:00'));
        self::assertSame('02/05/2018', $carbon::nextBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-04 12:00:00'));
        self::assertSame('05/04/2018', $carbon::nextBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-14 12:00:00'));
        self::assertSame('16/04/2018', $carbon::nextBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-15 12:00:00'));
        self::assertSame('16/04/2018', $carbon::nextBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-16 12:00:00'));
        self::assertSame('17/04/2018', $carbon::nextBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-11-11 12:00:00'));
        self::assertSame('12/11/2018', $carbon::nextBusinessDay()->format('d/m/Y'));
    }

    public function testCurrentOrNextBusinessDay()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('fr-national');
        self::assertSame('02/05/2018', $carbon::parse('2018-05-01 12:00:00')->currentOrNextBusinessDay()->format('d/m/Y'));
        self::assertSame('04/04/2018', $carbon::parse('2018-04-04 12:00:00')->currentOrNextBusinessDay()->format('d/m/Y'));
        self::assertSame('16/04/2018', $carbon::parse('2018-04-14 12:00:00')->currentOrNextBusinessDay()->format('d/m/Y'));
        self::assertSame('16/04/2018', $carbon::parse('2018-04-15 12:00:00')->currentOrNextBusinessDay()->format('d/m/Y'));
        self::assertSame('16/04/2018', $carbon::parse('2018-04-16 12:00:00')->currentOrNextBusinessDay()->format('d/m/Y'));
        self::assertSame('12/11/2018', $carbon::parse('2018-11-11 12:00:00')->currentOrNextBusinessDay()->format('d/m/Y'));
        $date = $carbon::parse('2018-04-16 12:00:00');
        self::assertSame($date, $date->currentOrNextBusinessDay());
        $date = $carbon::parse('2018-11-11 12:00:00');
        self::assertSame($date, $date->currentOrNextBusinessDay());
        self::assertSame('12/11/2018', $date->format('d/m/Y'));
    }

    public function testCurrentOrNextBusinessDayStatic()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('fr-national');
        $carbon::setTestNow($carbon::parse('2018-05-01 12:00:00'));
        self::assertSame('02/05/2018', $carbon::currentOrNextBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-04 12:00:00'));
        self::assertSame('04/04/2018', $carbon::currentOrNextBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-14 12:00:00'));
        self::assertSame('16/04/2018', $carbon::currentOrNextBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-15 12:00:00'));
        self::assertSame('16/04/2018', $carbon::currentOrNextBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-16 12:00:00'));
        self::assertSame('16/04/2018', $carbon::currentOrNextBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-11-11 12:00:00'));
        self::assertSame('12/11/2018', $carbon::currentOrNextBusinessDay()->format('d/m/Y'));
    }

    public function testPreviousBusinessDay()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('fr-national');
        self::assertSame('30/04/2018', $carbon::parse('2018-05-01 12:00:00')->previousBusinessDay()->format('d/m/Y'));
        self::assertSame('03/04/2018', $carbon::parse('2018-04-04 12:00:00')->previousBusinessDay()->format('d/m/Y'));
        self::assertSame('13/04/2018', $carbon::parse('2018-04-14 12:00:00')->previousBusinessDay()->format('d/m/Y'));
        self::assertSame('13/04/2018', $carbon::parse('2018-04-15 12:00:00')->previousBusinessDay()->format('d/m/Y'));
        self::assertSame('13/04/2018', $carbon::parse('2018-04-16 12:00:00')->previousBusinessDay()->format('d/m/Y'));
        self::assertSame('09/11/2018', $carbon::parse('2018-11-11 12:00:00')->previousBusinessDay()->format('d/m/Y'));
        $date = $carbon::parse('2018-04-16 12:00:00');
        self::assertSame($date, $date->previousBusinessDay());
        self::assertSame('13/04/2018', $date->format('d/m/Y'));
        $date = $carbon::parse('2018-11-11 12:00:00');
        self::assertSame($date, $date->previousBusinessDay());
        self::assertSame('09/11/2018', $date->format('d/m/Y'));
    }

    public function testPreviousBusinessDayStatic()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('fr-national');
        $carbon::setTestNow($carbon::parse('2018-05-01 12:00:00'));
        self::assertSame('30/04/2018', $carbon::previousBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-04 12:00:00'));
        self::assertSame('03/04/2018', $carbon::previousBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-14 12:00:00'));
        self::assertSame('13/04/2018', $carbon::previousBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-15 12:00:00'));
        self::assertSame('13/04/2018', $carbon::previousBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-16 12:00:00'));
        self::assertSame('13/04/2018', $carbon::previousBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-11-11 12:00:00'));
        self::assertSame('09/11/2018', $carbon::previousBusinessDay()->format('d/m/Y'));
    }

    public function testCurrentOrPreviousBusinessDay()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('fr-national');
        self::assertSame('30/04/2018', $carbon::parse('2018-05-01 12:00:00')->currentOrPreviousBusinessDay()->format('d/m/Y'));
        self::assertSame('04/04/2018', $carbon::parse('2018-04-04 12:00:00')->currentOrPreviousBusinessDay()->format('d/m/Y'));
        self::assertSame('13/04/2018', $carbon::parse('2018-04-14 12:00:00')->currentOrPreviousBusinessDay()->format('d/m/Y'));
        self::assertSame('13/04/2018', $carbon::parse('2018-04-15 12:00:00')->currentOrPreviousBusinessDay()->format('d/m/Y'));
        self::assertSame('16/04/2018', $carbon::parse('2018-04-16 12:00:00')->currentOrPreviousBusinessDay()->format('d/m/Y'));
        self::assertSame('09/11/2018', $carbon::parse('2018-11-11 12:00:00')->currentOrPreviousBusinessDay()->format('d/m/Y'));
        $date = $carbon::parse('2018-04-16 12:00:00');
        self::assertSame($date, $date->currentOrPreviousBusinessDay());
        self::assertSame('16/04/2018', $date->format('d/m/Y'));
        $date = $carbon::parse('2018-11-11 12:00:00');
        self::assertSame($date, $date->currentOrPreviousBusinessDay());
        self::assertSame('09/11/2018', $date->format('d/m/Y'));
    }

    public function testCurrentOrPreviousBusinessDayStatic()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('fr-national');
        $carbon::setTestNow($carbon::parse('2018-05-01 12:00:00'));
        self::assertSame('30/04/2018', $carbon::currentOrPreviousBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-04 12:00:00'));
        self::assertSame('04/04/2018', $carbon::currentOrPreviousBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-14 12:00:00'));
        self::assertSame('13/04/2018', $carbon::currentOrPreviousBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-15 12:00:00'));
        self::assertSame('13/04/2018', $carbon::currentOrPreviousBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-16 12:00:00'));
        self::assertSame('16/04/2018', $carbon::currentOrPreviousBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-11-11 12:00:00'));
        self::assertSame('09/11/2018', $carbon::currentOrPreviousBusinessDay()->format('d/m/Y'));
    }

    public function testAddBusinessDay()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('fr-national');
        self::assertSame('02/05/2018', $carbon::parse('2018-05-01 12:00:00')->addBusinessDay()->format('d/m/Y'));
        self::assertSame('05/04/2018', $carbon::parse('2018-04-04 12:00:00')->addBusinessDay()->format('d/m/Y'));
        self::assertSame('16/04/2018', $carbon::parse('2018-04-14 12:00:00')->addBusinessDay()->format('d/m/Y'));
        self::assertSame('16/04/2018', $carbon::parse('2018-04-15 12:00:00')->addBusinessDay()->format('d/m/Y'));
        self::assertSame('17/04/2018', $carbon::parse('2018-04-16 12:00:00')->addBusinessDay()->format('d/m/Y'));
        self::assertSame('12/11/2018', $carbon::parse('2018-11-11 12:00:00')->addBusinessDay()->format('d/m/Y'));
        self::assertSame('02/05/2018', $carbon::parse('2018-05-01 12:00:00')->addBusinessDays()->format('d/m/Y'));
        self::assertSame('05/04/2018', $carbon::parse('2018-04-04 12:00:00')->addBusinessDays()->format('d/m/Y'));
        self::assertSame('16/04/2018', $carbon::parse('2018-04-14 12:00:00')->addBusinessDays()->format('d/m/Y'));
        self::assertSame('16/04/2018', $carbon::parse('2018-04-15 12:00:00')->addBusinessDays()->format('d/m/Y'));
        self::assertSame('17/04/2018', $carbon::parse('2018-04-16 12:00:00')->addBusinessDays()->format('d/m/Y'));
        self::assertSame('12/11/2018', $carbon::parse('2018-11-11 12:00:00')->addBusinessDays()->format('d/m/Y'));
        self::assertSame('11/05/2018', $carbon::parse('2018-05-01 12:00:00')->addBusinessDays(6)->format('d/m/Y'));
        self::assertSame('17/04/2018', $carbon::parse('2018-04-04 12:00:00')->addBusinessDays(9)->format('d/m/Y'));
        self::assertSame('24/05/2018', $carbon::parse('2018-04-14 12:00:00')->addBusinessDays(25)->format('d/m/Y'));
        self::assertSame('18/04/2018', $carbon::parse('2018-04-15 12:00:00')->addBusinessDays(3)->format('d/m/Y'));
        self::assertSame('18/04/2018', $carbon::parse('2018-04-16 12:00:00')->addBusinessDays(2)->format('d/m/Y'));
        self::assertSame('04/12/2018', $carbon::parse('2018-11-11 12:00:00')->addBusinessDays(17)->format('d/m/Y'));
        $date = $carbon::parse('2018-11-11 12:00:00');
        self::assertSame($date, $date->addBusinessDays(17));
        self::assertSame('04/12/2018', $date->format('d/m/Y'));
    }

    public function testAddBusinessDayStatic()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('fr-national');
        $carbon::setTestNow($carbon::parse('2018-05-01 12:00:00'));
        self::assertSame('02/05/2018', $carbon::addBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-04 12:00:00'));
        self::assertSame('05/04/2018', $carbon::addBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-14 12:00:00'));
        self::assertSame('16/04/2018', $carbon::addBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-15 12:00:00'));
        self::assertSame('16/04/2018', $carbon::addBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-16 12:00:00'));
        self::assertSame('17/04/2018', $carbon::addBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-11-11 12:00:00'));
        self::assertSame('12/11/2018', $carbon::addBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-05-01 12:00:00'));
        self::assertSame('02/05/2018', $carbon::addBusinessDays()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-04 12:00:00'));
        self::assertSame('05/04/2018', $carbon::addBusinessDays()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-14 12:00:00'));
        self::assertSame('16/04/2018', $carbon::addBusinessDays()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-15 12:00:00'));
        self::assertSame('16/04/2018', $carbon::addBusinessDays()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-16 12:00:00'));
        self::assertSame('17/04/2018', $carbon::addBusinessDays()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-11-11 12:00:00'));
        self::assertSame('12/11/2018', $carbon::addBusinessDays()->format('d/m/Y'));
    }

    public function testSubBusinessDay()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('fr-national');
        self::assertSame('30/04/2018', $carbon::parse('2018-05-01 12:00:00')->subBusinessDay()->format('d/m/Y'));
        self::assertSame('03/04/2018', $carbon::parse('2018-04-04 12:00:00')->subBusinessDay()->format('d/m/Y'));
        self::assertSame('13/04/2018', $carbon::parse('2018-04-14 12:00:00')->subBusinessDay()->format('d/m/Y'));
        self::assertSame('13/04/2018', $carbon::parse('2018-04-15 12:00:00')->subBusinessDay()->format('d/m/Y'));
        self::assertSame('13/04/2018', $carbon::parse('2018-04-16 12:00:00')->subBusinessDay()->format('d/m/Y'));
        self::assertSame('09/11/2018', $carbon::parse('2018-11-11 12:00:00')->subBusinessDay()->format('d/m/Y'));
        self::assertSame('30/04/2018', $carbon::parse('2018-05-01 12:00:00')->subBusinessDays()->format('d/m/Y'));
        self::assertSame('03/04/2018', $carbon::parse('2018-04-04 12:00:00')->subBusinessDays()->format('d/m/Y'));
        self::assertSame('13/04/2018', $carbon::parse('2018-04-14 12:00:00')->subBusinessDays()->format('d/m/Y'));
        self::assertSame('13/04/2018', $carbon::parse('2018-04-15 12:00:00')->subBusinessDays()->format('d/m/Y'));
        self::assertSame('13/04/2018', $carbon::parse('2018-04-16 12:00:00')->subBusinessDays()->format('d/m/Y'));
        self::assertSame('09/11/2018', $carbon::parse('2018-11-11 12:00:00')->subBusinessDays()->format('d/m/Y'));
        self::assertSame('23/04/2018', $carbon::parse('2018-05-01 12:00:00')->subBusinessDays(6)->format('d/m/Y'));
        self::assertSame('21/03/2018', $carbon::parse('2018-04-04 12:00:00')->subBusinessDays(9)->format('d/m/Y'));
        self::assertSame('09/03/2018', $carbon::parse('2018-04-14 12:00:00')->subBusinessDays(25)->format('d/m/Y'));
        self::assertSame('11/04/2018', $carbon::parse('2018-04-15 12:00:00')->subBusinessDays(3)->format('d/m/Y'));
        self::assertSame('12/04/2018', $carbon::parse('2018-04-16 12:00:00')->subBusinessDays(2)->format('d/m/Y'));
        self::assertSame('17/10/2018', $carbon::parse('2018-11-11 12:00:00')->subBusinessDays(17)->format('d/m/Y'));
        $date = $carbon::parse('2018-11-11 12:00:00');
        self::assertSame($date, $date->subBusinessDays(17));
        self::assertSame('17/10/2018', $date->format('d/m/Y'));
    }

    public function testSubBusinessDayStatic()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('fr-national');
        $carbon::setTestNow($carbon::parse('2018-05-01 12:00:00'));
        self::assertSame('30/04/2018', $carbon::subBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-04 12:00:00'));
        self::assertSame('03/04/2018', $carbon::subBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-14 12:00:00'));
        self::assertSame('13/04/2018', $carbon::subBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-15 12:00:00'));
        self::assertSame('13/04/2018', $carbon::subBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-16 12:00:00'));
        self::assertSame('13/04/2018', $carbon::subBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-11-11 12:00:00'));
        self::assertSame('09/11/2018', $carbon::subBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-05-01 12:00:00'));
        self::assertSame('30/04/2018', $carbon::subBusinessDays()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-04 12:00:00'));
        self::assertSame('03/04/2018', $carbon::subBusinessDays()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-14 12:00:00'));
        self::assertSame('13/04/2018', $carbon::subBusinessDays()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-15 12:00:00'));
        self::assertSame('13/04/2018', $carbon::subBusinessDays()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-16 12:00:00'));
        self::assertSame('13/04/2018', $carbon::subBusinessDays()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-11-11 12:00:00'));
        self::assertSame('09/11/2018', $carbon::subBusinessDays()->format('d/m/Y'));
    }

    public function testGetHolidayId()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('fr-national');
        $date = $carbon::parse('2018-12-26 12:00:00');
        self::assertFalse($date->getHolidayId());
        $carbon::setHolidaysRegion('fr-east');
        self::assertSame('christmas-next-day', $date->getHolidayId());
        $carbon::setHolidaysRegion('si-national');
        self::assertSame('independence-day', $date->getHolidayId());
    }

    public function testObservedHolidaysZone()
    {
        $carbon = static::CARBON_CLASS;
        self::assertSame('default', $carbon::getObservedHolidaysZone());
        self::assertSame('default', $carbon::now()->getObservedHolidaysZone());
        $carbon::setObservedHolidaysZone('my-company');
        self::assertSame('my-company', $carbon::getObservedHolidaysZone());
        self::assertSame('my-company', $carbon::now()->getObservedHolidaysZone());
        $carbon::now()->setObservedHolidaysZone('foobar');
        self::assertSame('foobar', $carbon::getObservedHolidaysZone());
        self::assertSame('foobar', $carbon::now()->getObservedHolidaysZone());
    }

    public function testObserveHolidays()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('fr-national');
        self::assertFalse($carbon::isObservedHoliday('new-year'));
        self::assertFalse($carbon::isObservedHoliday('christmas'));
        self::assertFalse($carbon::parse('2018-01-01')->isObservedHoliday());
        self::assertFalse($carbon::parse('2018-12-25')->isObservedHoliday());
        self::assertFalse($carbon::parse('2018-12-26')->isObservedHoliday());
        $carbon::observeHoliday('christmas');
        self::assertFalse($carbon::isObservedHoliday('new-year'));
        self::assertTrue($carbon::isObservedHoliday('christmas'));
        self::assertFalse($carbon::parse('2018-01-01')->isObservedHoliday());
        self::assertTrue($carbon::parse('2018-12-25')->isObservedHoliday());
        self::assertFalse($carbon::parse('2018-12-26')->isObservedHoliday());
        $carbon::unobserveHoliday('christmas');
        self::assertFalse($carbon::isObservedHoliday('new-year'));
        self::assertFalse($carbon::isObservedHoliday('christmas'));
        self::assertFalse($carbon::parse('2018-01-01')->isObservedHoliday());
        self::assertFalse($carbon::parse('2018-12-25')->isObservedHoliday());
        self::assertFalse($carbon::parse('2018-12-26')->isObservedHoliday());
        $carbon::observeAllHolidays();
        self::assertTrue($carbon::isObservedHoliday('new-year'));
        self::assertTrue($carbon::isObservedHoliday('christmas'));
        self::assertTrue($carbon::parse('2018-01-01')->isObservedHoliday());
        self::assertTrue($carbon::parse('2018-12-25')->isObservedHoliday());
        self::assertFalse($carbon::parse('2018-12-26')->isObservedHoliday());
        $carbon::unobserveHoliday('christmas');
        self::assertTrue($carbon::isObservedHoliday('new-year'));
        self::assertFalse($carbon::isObservedHoliday('christmas'));
        self::assertTrue($carbon::parse('2018-01-01')->isObservedHoliday());
        self::assertFalse($carbon::parse('2018-12-25')->isObservedHoliday());
        self::assertFalse($carbon::parse('2018-12-26')->isObservedHoliday());
        $carbon::unobserveAllHolidays();
        self::assertFalse($carbon::isObservedHoliday('new-year'));
        self::assertFalse($carbon::isObservedHoliday('christmas'));
        self::assertFalse($carbon::parse('2018-01-01')->isObservedHoliday());
        self::assertFalse($carbon::parse('2018-12-25')->isObservedHoliday());
        self::assertFalse($carbon::parse('2018-12-26')->isObservedHoliday());
        $carbon::observeHolidays(array('christmas', 'new-year'));
        self::assertTrue($carbon::isObservedHoliday('new-year'));
        self::assertTrue($carbon::isObservedHoliday('christmas'));
        self::assertTrue($carbon::parse('2018-01-01')->isObservedHoliday());
        self::assertTrue($carbon::parse('2018-12-25')->isObservedHoliday());
        self::assertFalse($carbon::parse('2018-12-26')->isObservedHoliday());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage You must pass holiday names as a string or "all".
     */
    public function testObserveHolidaysInvalidArgument()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::observeHoliday(42);
    }
}
