<?php

namespace Tests\Cmixin\Holidays;

use Cmixin\BusinessDay;
use PHPUnit\Framework\TestCase;

class FrTest extends TestCase
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
        $carbon::setHolidaysRegion('fr-national');

        // 2018 year
        self::assertTrue($carbon::parse('2018-01-01')->isHoliday()); // New Year
        self::assertTrue($carbon::parse('2018-01-06')->isHoliday()); // Epiphany
        self::assertTrue($carbon::parse('2018-04-02')->isHoliday()); // Eastern Monday
        self::assertTrue($carbon::parse('2018-05-01')->isHoliday()); // Labor Day
        self::assertTrue($carbon::parse('2018-05-08')->isHoliday()); // Victory 1945
        self::assertTrue($carbon::parse('2018-05-10')->isHoliday()); // Ascension Thursday
        self::assertTrue($carbon::parse('2018-05-21')->isHoliday()); // Whit Monday
        self::assertTrue($carbon::parse('2018-07-14')->isHoliday()); // National Holiday
        self::assertTrue($carbon::parse('2018-08-15')->isHoliday()); // Assomption
        self::assertTrue($carbon::parse('2018-11-01')->isHoliday()); // All Saints
        self::assertTrue($carbon::parse('2018-11-11')->isHoliday()); // Armistice 1918
        self::assertTrue($carbon::parse('2018-12-25')->isHoliday()); // Christmas

        // 2019 year
        self::assertTrue($carbon::parse('2019-01-01')->isHoliday()); // New Year
        self::assertTrue($carbon::parse('2019-01-06')->isHoliday()); // Epiphany
        self::assertTrue($carbon::parse('2019-04-22')->isHoliday()); // Eastern Monday
        self::assertTrue($carbon::parse('2019-05-01')->isHoliday()); // Labor Day
        self::assertTrue($carbon::parse('2019-05-08')->isHoliday()); // Victory 1945
        self::assertTrue($carbon::parse('2019-05-30')->isHoliday()); // Ascension Thursday
        self::assertTrue($carbon::parse('2019-06-10')->isHoliday()); // Whit Monday
        self::assertTrue($carbon::parse('2019-07-14')->isHoliday()); // National Holiday
        self::assertTrue($carbon::parse('2019-08-15')->isHoliday()); // Assomption
        self::assertTrue($carbon::parse('2019-11-01')->isHoliday()); // All Saints
        self::assertTrue($carbon::parse('2019-11-11')->isHoliday()); // Armistice 1918
        self::assertTrue($carbon::parse('2019-12-25')->isHoliday()); // Christmas
    }
}
