<?php

namespace Tests\Cmixin\Traits;

trait AddAndSubDays
{
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
        self::assertSame('12/11/2018', $carbon::addBusinessDays(new \DateTime('2018-11-11 12:00:00'))->format('d/m/Y'));
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
    }

    public function testSubtractBusinessDay()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('fr-national');
        self::assertSame('30/04/2018', $carbon::parse('2018-05-01 12:00:00')->subtractBusinessDay()->format('d/m/Y'));
        self::assertSame('03/04/2018', $carbon::parse('2018-04-04 12:00:00')->subtractBusinessDay()->format('d/m/Y'));
        self::assertSame('13/04/2018', $carbon::parse('2018-04-14 12:00:00')->subtractBusinessDay()->format('d/m/Y'));
        self::assertSame('13/04/2018', $carbon::parse('2018-04-15 12:00:00')->subtractBusinessDay()->format('d/m/Y'));
        self::assertSame('13/04/2018', $carbon::parse('2018-04-16 12:00:00')->subtractBusinessDay()->format('d/m/Y'));
        self::assertSame('09/11/2018', $carbon::parse('2018-11-11 12:00:00')->subtractBusinessDay()->format('d/m/Y'));
        self::assertSame('30/04/2018', $carbon::parse('2018-05-01 12:00:00')->subtractBusinessDays()->format('d/m/Y'));
        self::assertSame('03/04/2018', $carbon::parse('2018-04-04 12:00:00')->subtractBusinessDays()->format('d/m/Y'));
        self::assertSame('13/04/2018', $carbon::parse('2018-04-14 12:00:00')->subtractBusinessDays()->format('d/m/Y'));
        self::assertSame('13/04/2018', $carbon::parse('2018-04-15 12:00:00')->subtractBusinessDays()->format('d/m/Y'));
        self::assertSame('13/04/2018', $carbon::parse('2018-04-16 12:00:00')->subtractBusinessDays()->format('d/m/Y'));
        self::assertSame('09/11/2018', $carbon::parse('2018-11-11 12:00:00')->subtractBusinessDays()->format('d/m/Y'));
        self::assertSame('23/04/2018', $carbon::parse('2018-05-01 12:00:00')->subtractBusinessDays(6)->format('d/m/Y'));
        self::assertSame('21/03/2018', $carbon::parse('2018-04-04 12:00:00')->subtractBusinessDays(9)->format('d/m/Y'));
        self::assertSame('09/03/2018', $carbon::parse('2018-04-14 12:00:00')->subtractBusinessDays(25)->format('d/m/Y'));
        self::assertSame('11/04/2018', $carbon::parse('2018-04-15 12:00:00')->subtractBusinessDays(3)->format('d/m/Y'));
        self::assertSame('12/04/2018', $carbon::parse('2018-04-16 12:00:00')->subtractBusinessDays(2)->format('d/m/Y'));
        self::assertSame('17/10/2018', $carbon::parse('2018-11-11 12:00:00')->subtractBusinessDays(17)->format('d/m/Y'));
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

    public function testSubtractBusinessDayStatic()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('fr-national');
        $carbon::setTestNow($carbon::parse('2018-05-01 12:00:00'));
        self::assertSame('30/04/2018', $carbon::subtractBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-04 12:00:00'));
        self::assertSame('03/04/2018', $carbon::subtractBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-14 12:00:00'));
        self::assertSame('13/04/2018', $carbon::subtractBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-15 12:00:00'));
        self::assertSame('13/04/2018', $carbon::subtractBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-16 12:00:00'));
        self::assertSame('13/04/2018', $carbon::subtractBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-11-11 12:00:00'));
        self::assertSame('09/11/2018', $carbon::subtractBusinessDay()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-05-01 12:00:00'));
        self::assertSame('30/04/2018', $carbon::subtractBusinessDays()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-04 12:00:00'));
        self::assertSame('03/04/2018', $carbon::subtractBusinessDays()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-14 12:00:00'));
        self::assertSame('13/04/2018', $carbon::subtractBusinessDays()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-15 12:00:00'));
        self::assertSame('13/04/2018', $carbon::subtractBusinessDays()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-04-16 12:00:00'));
        self::assertSame('13/04/2018', $carbon::subtractBusinessDays()->format('d/m/Y'));
        $carbon::setTestNow($carbon::parse('2018-11-11 12:00:00'));
        self::assertSame('09/11/2018', $carbon::subtractBusinessDays()->format('d/m/Y'));
    }
}