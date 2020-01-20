<?php

namespace Tests\Cmixin\Holidays;

use Cmixin\BusinessDay;
use PHPUnit\Framework\TestCase;

class PaTest extends TestCase
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
        $carbon::setHolidaysRegion('pa-national');

        self::assertTrue($carbon::parse('2014-07-01')->isHoliday());
        self::assertFalse($carbon::parse('2015-07-01')->isHoliday());
        self::assertFalse($carbon::parse('2016-07-01')->isHoliday());
        self::assertFalse($carbon::parse('2017-07-01')->isHoliday());
        self::assertFalse($carbon::parse('2018-07-01')->isHoliday());
        self::assertTrue($carbon::parse('2019-07-01')->isHoliday());
        self::assertFalse($carbon::parse('2020-07-01')->isHoliday());
        self::assertFalse($carbon::parse('2021-07-01')->isHoliday());
        self::assertFalse($carbon::parse('2022-07-01')->isHoliday());
        self::assertFalse($carbon::parse('2023-07-01')->isHoliday());
        self::assertTrue($carbon::parse('2024-07-01')->isHoliday());
    }
}
