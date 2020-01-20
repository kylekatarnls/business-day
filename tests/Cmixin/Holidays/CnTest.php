<?php

namespace Tests\Cmixin\Holidays;

use Cmixin\BusinessDay;
use PHPUnit\Framework\TestCase;

class CnTest extends TestCase
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
        $carbon::setHolidaysRegion('cn-national');

        self::assertFalse($carbon::parse('2019-02-03')->isHoliday());
        self::assertTrue($carbon::parse('2019-02-04')->isHoliday());
        self::assertTrue($carbon::parse('2019-02-05')->isHoliday());
        self::assertFalse($carbon::parse('2019-02-06')->isHoliday());

        self::assertFalse($carbon::parse('2019-06-06')->isHoliday());
        self::assertTrue($carbon::parse('2019-06-07')->isHoliday());
        self::assertFalse($carbon::parse('2019-06-08')->isHoliday());

        self::assertFalse($carbon::parse('2020-01-23')->isHoliday());
        self::assertTrue($carbon::parse('2020-01-24')->isHoliday());
        self::assertTrue($carbon::parse('2020-01-25')->isHoliday());
        self::assertTrue($carbon::parse('2020-01-25')->isHoliday());
        self::assertFalse($carbon::parse('2020-01-26')->isHoliday());
    }
}
