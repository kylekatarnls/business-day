<?php

namespace Tests\Cmixin\Holidays;

use Cmixin\BusinessDay;
use PHPUnit\Framework\TestCase;

class PlTest extends TestCase
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
        $carbon::setHolidaysRegion('pl-national');

        self::assertFalse($carbon::parse('2024-12-24')->isHoliday());
        self::assertFalse($carbon::parse('2023-12-24')->isHoliday());
        self::assertTrue($carbon::parse('2025-12-24')->isHoliday());
        self::assertTrue($carbon::parse('2026-12-24')->isHoliday());
    }
}
