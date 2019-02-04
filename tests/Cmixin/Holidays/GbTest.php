<?php

namespace Tests\Cmixin\Holidays;

use Cmixin\BusinessDay;
use PHPUnit\Framework\TestCase;

class GbTest extends TestCase
{
    const CARBON_CLASS = 'Carbon\Carbon';

    protected function setUp()
    {
        BusinessDay::enable(static::CARBON_CLASS);
        $carbon = static::CARBON_CLASS;
        $carbon::resetHolidays();
    }

    public function testHolidaysSpecificDates()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('gb-national');

        // 2019 year
        self::assertTrue($carbon::parse('2019-04-19')->isHoliday()); // Good Friday
        self::assertTrue($carbon::parse('2019-05-06')->isHoliday()); // Early May
        self::assertTrue($carbon::parse('2019-05-27')->isHoliday()); // Spring
        self::assertTrue($carbon::parse('2019-12-25')->isHoliday()); // Christmas
        self::assertTrue($carbon::parse('2019-12-26')->isHoliday()); // Boxing Day

        // 2020 year
        self::assertTrue($carbon::parse('2020-04-10')->isHoliday()); // Good Friday
        self::assertTrue($carbon::parse('2020-05-04')->isHoliday()); // Early May
        self::assertTrue($carbon::parse('2020-05-25')->isHoliday()); // Spring
        self::assertTrue($carbon::parse('2020-12-25')->isHoliday()); // Christmas
        self::assertTrue($carbon::parse('2020-12-28')->isHoliday()); // Boxing Day (Substitute)

        $carbon::setHolidaysRegion('gb-engwales');

        // 2019 year
        self::assertTrue($carbon::parse('2019-01-01')->isHoliday()); // New Year
        self::assertTrue($carbon::parse('2019-04-22')->isHoliday()); // Easter Monday
        self::assertTrue($carbon::parse('2019-08-26')->isHoliday()); // Summer

        // 2020 year
        self::assertTrue($carbon::parse('2020-01-01')->isHoliday()); // New Year
        self::assertTrue($carbon::parse('2020-04-13')->isHoliday()); // Easter Monday
        self::assertTrue($carbon::parse('2020-08-31')->isHoliday()); // Summer

        $carbon::setHolidaysRegion('gb-nireland');

        // 2019 year
        self::assertTrue($carbon::parse('2019-01-01')->isHoliday()); // New Year
        self::assertTrue($carbon::parse('2019-03-18')->isHoliday()); // St Patrick's (Substitute)
        self::assertTrue($carbon::parse('2019-04-22')->isHoliday()); // Easter Monday
        self::assertTrue($carbon::parse('2019-07-12')->isHoliday()); // Boyne
        self::assertTrue($carbon::parse('2019-08-26')->isHoliday()); // Summer

        // 2020 year
        self::assertTrue($carbon::parse('2020-01-01')->isHoliday()); // New Year
        self::assertTrue($carbon::parse('2020-03-17')->isHoliday()); // St Patrick's
        self::assertTrue($carbon::parse('2020-04-13')->isHoliday()); // Easter Monday
        self::assertTrue($carbon::parse('2020-07-13')->isHoliday()); // Boyne (Substitute)
        self::assertTrue($carbon::parse('2020-08-31')->isHoliday()); // Summer

        $carbon::setHolidaysRegion('gb-scotland');

        // 2019 year
        self::assertTrue($carbon::parse('2020-01-01')->isHoliday()); // New Year
        self::assertTrue($carbon::parse('2019-01-02')->isHoliday()); // 2nd January
        self::assertTrue($carbon::parse('2019-08-05')->isHoliday()); // Summer
        self::assertTrue($carbon::parse('2019-12-02')->isHoliday()); // St Andrew's (Substitute)

        // 2020 year
        self::assertTrue($carbon::parse('2020-01-01')->isHoliday()); // New Year
        self::assertTrue($carbon::parse('2020-01-02')->isHoliday()); // 2nd January
        self::assertTrue($carbon::parse('2020-08-03')->isHoliday()); // Summer
        self::assertTrue($carbon::parse('2020-11-30')->isHoliday()); // St Andrew's
    }
}
