<?php

namespace Tests\Cmixin\Holidays;

use Cmixin\BusinessDay;
use PHPUnit\Framework\TestCase;

class BrTest extends TestCase
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
        $carbon::setHolidaysRegion('br-national');

        self::assertFalse($carbon::parse('2017-10-01')->isHoliday());
        self::assertTrue($carbon::parse('2018-10-07')->isHoliday());
        self::assertFalse($carbon::parse('2019-10-06')->isHoliday());
        self::assertTrue($carbon::parse('2020-10-04')->isHoliday());
        self::assertFalse($carbon::parse('2021-10-03')->isHoliday());
    }
}
