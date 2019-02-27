<?php
/**
 * Author: Herman Slatman
 * Date: 20/10/2018
 * Time: 00:11.
 */

namespace Tests\Cmixin\Holidays;

use Cmixin\BusinessDay;
use PHPUnit\Framework\TestCase;

class NlTest extends TestCase
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
        $carbon::setHolidaysRegion('nl-national');

        self::assertTrue($carbon::parse('2000-01-01 00:00:00')->isHoliday());
        self::assertTrue($carbon::parse('2018-04-27')->isHoliday());
        self::assertFalse($carbon::parse('2018-04-26')->isHoliday());
        self::assertFalse($carbon::parse('2014-04-27')->isHoliday());
        self::assertTrue($carbon::parse('2014-04-26')->isHoliday());
        self::assertTrue($carbon::parse('2018-04-01')->isHoliday());
        self::assertTrue($carbon::parse('2018-04-02')->isHoliday());
        self::assertFalse($carbon::parse('2018-05-05')->isHoliday()); // Liberation Day; only once every 5 years
        self::assertTrue($carbon::parse('2020-05-05')->isHoliday());
        self::assertTrue($carbon::parse('2018-05-10')->isHoliday());
        self::assertTrue($carbon::parse('2018-05-20')->isHoliday());
        self::assertTrue($carbon::parse('2018-05-21')->isHoliday());
        self::assertTrue($carbon::parse('2000-12-25 00:00:00')->isHoliday());
        self::assertTrue($carbon::parse('2000-12-26 00:00:00')->isHoliday());

        self::assertTrue($carbon::parse('2019-04-21')->isHoliday());
        self::assertTrue($carbon::parse('2019-04-22')->isHoliday());
        self::assertTrue($carbon::parse('2020-04-12')->isHoliday());
        self::assertTrue($carbon::parse('2020-04-13')->isHoliday());
        self::assertTrue($carbon::parse('2021-04-04')->isHoliday());
        self::assertTrue($carbon::parse('2021-04-05')->isHoliday());
        self::assertTrue($carbon::parse('2022-04-17')->isHoliday());
        self::assertTrue($carbon::parse('2022-04-18')->isHoliday());
        self::assertTrue($carbon::parse('2023-04-09')->isHoliday());
        self::assertTrue($carbon::parse('2023-04-10')->isHoliday());
        self::assertTrue($carbon::parse('2024-03-31')->isHoliday());
        self::assertTrue($carbon::parse('2024-04-01')->isHoliday());
        self::assertTrue($carbon::parse('2025-04-20')->isHoliday());
        self::assertTrue($carbon::parse('2025-04-21')->isHoliday());
        self::assertTrue($carbon::parse('2026-04-05')->isHoliday());
        self::assertTrue($carbon::parse('2026-04-06')->isHoliday());
        self::assertTrue($carbon::parse('2027-03-28')->isHoliday());
        self::assertTrue($carbon::parse('2027-03-29')->isHoliday());
        self::assertTrue($carbon::parse('2028-04-16')->isHoliday());
        self::assertTrue($carbon::parse('2028-04-17')->isHoliday());
        self::assertTrue($carbon::parse('2029-04-01')->isHoliday());
        self::assertTrue($carbon::parse('2029-04-02')->isHoliday());

        self::assertTrue($carbon::parse('2019-01-01 00:00:00')->isHoliday());
        self::assertTrue($carbon::parse('2019-04-21')->isHoliday());
        self::assertTrue($carbon::parse('2019-04-22')->isHoliday());
        self::assertTrue($carbon::parse('2019-04-27')->isHoliday());
        self::assertTrue($carbon::parse('2019-05-30')->isHoliday());
        self::assertTrue($carbon::parse('2019-06-09')->isHoliday());
        self::assertTrue($carbon::parse('2019-06-10')->isHoliday());
        self::assertTrue($carbon::parse('2019-12-25 00:00:00')->isHoliday());
        self::assertTrue($carbon::parse('2019-12-26 00:00:00')->isHoliday());

        self::assertTrue($carbon::parse('2020-01-01 00:00:00')->isHoliday());
        self::assertTrue($carbon::parse('2020-04-12')->isHoliday());
        self::assertTrue($carbon::parse('2020-04-13')->isHoliday());
        self::assertTrue($carbon::parse('2020-04-27')->isHoliday());
        self::assertTrue($carbon::parse('2020-05-05')->isHoliday());
        self::assertTrue($carbon::parse('2020-05-21')->isHoliday());
        self::assertTrue($carbon::parse('2020-05-31')->isHoliday());
        self::assertTrue($carbon::parse('2020-06-01')->isHoliday());
        self::assertTrue($carbon::parse('2020-12-25 00:00:00')->isHoliday());
        self::assertTrue($carbon::parse('2020-12-26 00:00:00')->isHoliday());

        self::assertTrue($carbon::parse('2025-04-26')->isHoliday()); // Kingsday on Sunday :-> Saturday
    }
}
