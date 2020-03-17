<?php

namespace Tests\Cmixin\Holidays;

use Cmixin\BusinessDay;
use PHPUnit\Framework\TestCase;

class FrTest extends TestCase
{
    const CARBON_CLASS = 'Carbon\Carbon';

    protected function setUp(): void
    {
        BusinessDay::enable(static::CARBON_CLASS);
        $carbon = static::CARBON_CLASS;
        $carbon::resetHolidays();
    }

    public function testUnificationDay()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('it-national');

        // 2011 year
        self::assertTrue($carbon::parse('2011-03-17')->isHoliday());

        // other year
        self::assertFalse($carbon::parse('2012-03-17')->isHoliday());
    }
}
