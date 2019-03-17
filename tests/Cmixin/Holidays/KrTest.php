<?php

namespace Tests\Cmixin\Holidays;

use Cmixin\BusinessDay;
use PHPUnit\Framework\TestCase;

class KrTest extends TestCase
{
    const CARBON_CLASS = 'Carbon\Carbon';

    protected function setUp()
    {
        BusinessDay::enable(static::CARBON_CLASS);
        $carbon = static::CARBON_CLASS;
        $carbon::resetHolidays();
    }

    public function testHolidays()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::resetHolidays();
        $carbon::setHolidaysRegion('kr-national');

        self::assertFalse($carbon::parse('2019-02-03')->isHoliday());
        self::assertTrue($carbon::parse('2019-02-04')->isHoliday());
        self::assertTrue($carbon::parse('2019-02-05')->isHoliday());
        self::assertTrue($carbon::parse('2019-02-06')->isHoliday());
        self::assertFalse($carbon::parse('2019-02-08')->isHoliday());

        self::assertFalse($carbon::parse('2019-09-11')->isHoliday());
        self::assertTrue($carbon::parse('2019-09-12')->isHoliday());
        self::assertTrue($carbon::parse('2019-09-13')->isHoliday());
        self::assertTrue($carbon::parse('2019-09-14')->isHoliday());
        self::assertFalse($carbon::parse('2019-09-16')->isHoliday());
    }
}
