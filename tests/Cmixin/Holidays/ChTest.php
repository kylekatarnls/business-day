<?php

namespace Tests\Cmixin\Holidays;

use Cmixin\BusinessDay;
use PHPUnit\Framework\TestCase;

class ChTest extends TestCase
{
    const CARBON_CLASS = 'Carbon\Carbon';

    protected function setUp()
    {
        BusinessDay::enable(static::CARBON_CLASS);
        $carbon = static::CARBON_CLASS;
        $carbon::resetHolidays();
    }

    public function testHolidaysSpecificDates()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('ch-SG');

        self::assertTrue($carbon::parse('2018-11-01')->isHoliday());
        self::assertTrue($carbon::parse('2018-08-01')->isHoliday());
        self::assertTrue($carbon::parse('2018-12-26')->isHoliday());
        self::assertFalse($carbon::parse('2018-09-06')->isHoliday());
        self::assertFalse($carbon::parse('2018-09-17')->isHoliday());

        $carbon::setHolidaysRegion('ch-GE');

        self::assertTrue($carbon::parse('2018-08-01')->isHoliday());
        self::assertTrue($carbon::parse('2018-09-06')->isHoliday());
        self::assertFalse($carbon::parse('2018-09-17')->isHoliday());
        self::assertFalse($carbon::parse('2018-12-26')->isHoliday());
        self::assertTrue($carbon::parse('2018-12-31')->isHoliday());

        $carbon::setHolidaysRegion('ch-VD');

        self::assertTrue($carbon::parse('2018-01-02')->isHoliday());
        self::assertTrue($carbon::parse('2018-08-01')->isHoliday());
        self::assertFalse($carbon::parse('2018-09-06')->isHoliday());
        self::assertTrue($carbon::parse('2018-09-17')->isHoliday());
        self::assertTrue($carbon::parse('2018-12-26')->isHoliday());
        self::assertFalse($carbon::parse('2018-12-31')->isHoliday());
    }
}
