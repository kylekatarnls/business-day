<?php

namespace Tests\Cmixin\Holidays;

use Cmixin\BusinessDay;
use PHPUnit\Framework\TestCase;

class DkTest extends TestCase
{
    const CARBON_CLASS = 'Carbon\Carbon';

    protected function setUp(): void
    {
        BusinessDay::enable(static::CARBON_CLASS);
        $carbon = static::CARBON_CLASS;
        $carbon::resetHolidays();
    }

    public function testHolidays(): void
    {
        $carbon = static::CARBON_CLASS;
        $carbon::resetHolidays();
        $carbon::setHolidaysRegion('dk-national');

        self::assertTrue($carbon::parse('2023-05-05')->isHoliday());
        self::assertSame('easter-26', $carbon::parse('2023-05-05')->getHolidayId());
        self::assertSame('Prayer Day', $carbon::parse('2023-05-05')->getHolidayName());
        self::assertSame('Store Bededag', $carbon::parse('2023-05-05')->getHolidayName('da'));
        self::assertFalse($carbon::parse('2024-04-26')->isHoliday());
        self::assertFalse($carbon::parse('2024-04-26')->getHolidayId());
        self::assertFalse($carbon::parse('2024-04-26')->getHolidayName());
        self::assertFalse($carbon::parse('2024-04-26')->getHolidayName('da'));
    }
}
