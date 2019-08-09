<?php

namespace Tests\Cmixin;

class BusinessDayImmutableTest extends BusinessDayTest
{
    const CARBON_CLASS = 'Carbon\CarbonImmutable';

    protected function setUp()
    {
        if (!class_exists(static::CARBON_CLASS)) {
            $this->markTestSkipped('CarbonImmutable test skipped as it does not exist in Carbon < 2.0.0');
        }

        parent::setUp();
    }

    public function testMutability()
    {
        $carbon = static::CARBON_CLASS;

        $date = $carbon::parse('2018-04-16 12:00:00');
        self::assertSame($date, $date->currentOrNextBusinessDay());

        $date = $carbon::parse('2018-11-11 12:00:00');
        $result = $date->currentOrNextBusinessDay();
        self::assertNotSame($date, $result);
        self::assertSame('11/11/2018', $date->format('d/m/Y'));
        self::assertSame('12/11/2018', $result->format('d/m/Y'));

        $date = $carbon::parse('2018-04-16 12:00:00');
        $result = $date->previousBusinessDay();
        self::assertNotSame($date, $result);
        self::assertSame('16/04/2018', $date->format('d/m/Y'));
        self::assertSame('13/04/2018', $result->format('d/m/Y'));

        $date = $carbon::parse('2018-11-11 12:00:00');
        $result = $date->previousBusinessDay();
        self::assertNotSame($date, $result);
        self::assertSame('11/11/2018', $date->format('d/m/Y'));
        self::assertSame('09/11/2018', $result->format('d/m/Y'));

        $date = $carbon::parse('2018-04-16 12:00:00');
        $result = $date->nextBusinessDay();
        self::assertNotSame($date, $result);
        self::assertSame('16/04/2018', $date->format('d/m/Y'));
        self::assertSame('17/04/2018', $result->format('d/m/Y'));

        $date = $carbon::parse('2018-11-11 12:00:00');
        $result = $date->nextBusinessDay();
        self::assertNotSame($date, $result);
        self::assertSame('11/11/2018', $date->format('d/m/Y'));
        self::assertSame('12/11/2018', $result->format('d/m/Y'));

        $date = $carbon::parse('2018-04-16 12:00:00');
        $result = $date->currentOrPreviousBusinessDay();
        self::assertSame($date, $result);
        self::assertSame('16/04/2018', $date->format('d/m/Y'));
        self::assertSame('16/04/2018', $result->format('d/m/Y'));

        $date = $carbon::parse('2018-11-11 12:00:00');
        $result = $date->currentOrPreviousBusinessDay();
        self::assertNotSame($date, $result);
        self::assertSame('11/11/2018', $date->format('d/m/Y'));
        self::assertSame('09/11/2018', $result->format('d/m/Y'));

        $date = $carbon::parse('2018-11-11 12:00:00');
        $result = $date->addBusinessDays(17);
        self::assertNotSame($date, $result);
        self::assertSame('11/11/2018', $date->format('d/m/Y'));
        self::assertSame('04/12/2018', $result->format('d/m/Y'));

        $carbon::setHolidaysRegion('fr-national');

        $date = $carbon::parse('2018-11-11 12:00:00');
        $result = $date->subBusinessDays(17);
        self::assertNotSame($date, $result);
        self::assertSame('11/11/2018', $date->format('d/m/Y'));
        self::assertSame('17/10/2018', $result->format('d/m/Y'));

        $date = $carbon::parse('2018-11-11 12:00:00');
        $result = $date->subtractBusinessDays(17);
        self::assertNotSame($date, $result);
        self::assertSame('11/11/2018', $date->format('d/m/Y'));
        self::assertSame('17/10/2018', $result->format('d/m/Y'));
    }
}
