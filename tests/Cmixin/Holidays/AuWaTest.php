<?php

namespace Tests\Cmixin\Holidays;

use Cmixin\BusinessDay;
use PHPUnit\Framework\TestCase;

class AuWaTest extends TestCase
{
    const CARBON_CLASS = 'Carbon\Carbon';

    protected function setUp(): void
    {
        BusinessDay::enable(static::CARBON_CLASS);
        $carbon = static::CARBON_CLASS;
        $carbon::resetHolidays();
    }

    public function testHolidays()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::resetHolidays();
        $carbon::setHolidaysRegion('au-wa');

        self::assertTrue($carbon::parse('2019-01-01')->isHoliday());
        self::assertTrue($carbon::parse('2019-01-28')->isHoliday());
        self::assertTrue($carbon::parse('2019-03-04')->isHoliday());
        self::assertTrue($carbon::parse('2019-04-19')->isHoliday());
        self::assertTrue($carbon::parse('2019-04-22')->isHoliday());
        self::assertTrue($carbon::parse('2019-04-25')->isHoliday());
        self::assertTrue($carbon::parse('2019-06-03')->isHoliday());
        self::assertTrue($carbon::parse('2019-09-30')->isHoliday());
        self::assertTrue($carbon::parse('2019-12-25')->isHoliday());
        self::assertTrue($carbon::parse('2019-12-26')->isHoliday());
        self::assertFalse($carbon::parse('2021-06-14')->isHoliday());
    }
}
