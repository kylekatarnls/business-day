<?php

namespace Tests\Cmixin\Holidays;

use Cmixin\BusinessDay;
use PHPUnit\Framework\TestCase;

class PhTest extends TestCase
{
    const CARBON_CLASS = 'Carbon\Carbon';

    protected function setUp()
    {
        BusinessDay::enable(static::CARBON_CLASS);
        $carbon = static::CARBON_CLASS;
        $carbon::resetHolidays();
    }

    public function testHolidays()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::resetHolidays();
        $carbon::setHolidaysRegion('ph-national');

        self::assertFalse($carbon::parse('2019-06-05')->isHoliday());
        self::assertTrue($carbon::parse('2019-06-06')->isHoliday());
        self::assertFalse($carbon::parse('2019-06-07')->isHoliday());
        self::assertFalse($carbon::parse('2019-08-11')->isHoliday());
        self::assertTrue($carbon::parse('2019-08-12')->isHoliday());
        self::assertFalse($carbon::parse('2019-08-13')->isHoliday());
    }
}
