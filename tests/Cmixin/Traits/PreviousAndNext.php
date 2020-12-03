<?php

namespace Tests\Cmixin\Traits;

trait PreviousAndNext
{
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
}
