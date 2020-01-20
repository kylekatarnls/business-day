<?php

namespace Tests\Cmixin\Holidays;

use Cmixin\BusinessDay;
use PHPUnit\Framework\TestCase;

class BgTest extends TestCase
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
        $carbon::setHolidaysRegion('bg-national');

        self::assertFalse($carbon::parse('2019-04-25')->isHoliday());
        self::assertTrue($carbon::parse('2019-04-26')->isHoliday());
        self::assertTrue($carbon::parse('2019-04-27')->isHoliday());
        self::assertTrue($carbon::parse('2019-04-28')->isHoliday());
        self::assertTrue($carbon::parse('2019-04-29')->isHoliday());
        self::assertFalse($carbon::parse('2019-04-30')->isHoliday());
    }
}
