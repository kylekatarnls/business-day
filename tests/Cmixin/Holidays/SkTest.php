<?php

namespace Tests\Cmixin\Holidays;

use Cmixin\BusinessDay;
use PHPUnit\Framework\TestCase;

class SkTest extends TestCase
{
    const CARBON_CLASS = 'Carbon\Carbon';

    public function testHolidays(): void
    {
        BusinessDay::enable(static::CARBON_CLASS);
        $carbon = static::CARBON_CLASS;
        $carbon::resetHolidays();
        $carbon::setHolidaysRegion('sk-national');

        self::assertFalse($carbon::parse('1991-09-01')->isHoliday());
        self::assertTrue($carbon::parse('1992-09-01')->isHoliday());
        self::assertTrue($carbon::parse('2023-09-01')->isHoliday());
        self::assertFalse($carbon::parse('2024-09-01')->isHoliday());
        self::assertFalse($carbon::parse('2025-09-01')->isHoliday());
    }
}
