<?php

namespace Tests\Cmixin\Holidays;

use Cmixin\BusinessDay;
use PHPUnit\Framework\TestCase;

class DeTest extends TestCase
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
        $carbon::setHolidaysRegion('de-national');

        self::assertFalse($carbon::parse('2024-03-31')->getHolidayName());
        self::assertFalse($carbon::parse('2024-10-31')->getHolidayName());
        self::assertSame('Reformation Day', $carbon::parse('2017-10-31')->getHolidayName());

        $carbon::resetHolidays();
        $carbon::setHolidaysRegion('de-bb');

        self::assertSame('Easter', $carbon::parse('2024-03-31')->getHolidayName());
    }
}
