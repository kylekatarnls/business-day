<?php

namespace Tests\Cmixin\Holidays;

use Cmixin\BusinessDay;
use PHPUnit\Framework\TestCase;

class PhTest extends TestCase
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
        $carbon::setHolidaysRegion('ph-national');

        // @TODO: handle when Eid-al-Fitr is shifted by a better rule than using 2 Shawwal
        // It's June 5th for Morocco, when it's June 6th for Philippines
        self::assertFalse($carbon::parse('2019-06-05')->isHoliday());
        self::assertTrue($carbon::parse('2019-06-06')->isHoliday());
        self::assertFalse($carbon::parse('2019-06-07')->isHoliday());

        self::assertFalse($carbon::parse('2019-08-11')->isHoliday());
        self::assertTrue($carbon::parse('2019-08-12')->isHoliday());
        self::assertFalse($carbon::parse('2019-08-13')->isHoliday());

        self::assertFalse($carbon::parse('2020-05-24')->isHoliday());
        self::assertTrue($carbon::parse('2020-05-25')->isHoliday());
        self::assertFalse($carbon::parse('2020-05-26')->isHoliday());
    }
}
