<?php

namespace Tests\Cmixin\Holidays;

use PHPUnit\Framework\TestCase;

class SiTest extends TestCase
{
    const CARBON_CLASS = 'Carbon\Carbon';

    public function testHolidays()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::resetHolidays();
        $carbon::setHolidaysRegion('si-national');

        self::assertTrue($carbon::parse('2019-01-01')->isHoliday());
        self::assertTrue($carbon::parse('2019-04-21')->isHoliday());
        self::assertTrue($carbon::parse('2019-04-22')->isHoliday());
        self::assertTrue($carbon::parse('2019-04-27')->isHoliday());
        self::assertTrue($carbon::parse('2019-06-09')->isHoliday());
    }
}
