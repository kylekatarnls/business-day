<?php

namespace Tests\Cmixin\Holidays;

use Cmixin\BusinessDay;
use PHPUnit\Framework\TestCase;

class ArTest extends TestCase
{
    const CARBON_CLASS = 'Carbon\Carbon';

    protected function setUp(): void
    {
        BusinessDay::enable(static::CARBON_CLASS);
        $carbon = static::CARBON_CLASS;
        $carbon::resetHolidays();
    }

    public function testHolidaysSpecificDates()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('ar-national');

        self::assertTrue($carbon::parse('2021-10-08')->isHoliday());
        self::assertTrue($carbon::parse('2021-10-11')->isHoliday());
        self::assertFalse($carbon::parse('2021-10-12')->isHoliday());

        self::assertTrue($carbon::parse('2021-11-20')->isHoliday());
        self::assertTrue($carbon::parse('2021-11-22')->isHoliday());

        self::assertFalse($carbon::parse('2022-10-08')->isHoliday());
        self::assertFalse($carbon::parse('2022-10-11')->isHoliday());
        self::assertFalse($carbon::parse('2022-10-12')->isHoliday());

        self::assertTrue($carbon::parse('2022-11-20')->isHoliday());
        self::assertFalse($carbon::parse('2022-11-22')->isHoliday());
    }
}
