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

    public function testHolidays(): void
    {
        $carbon = static::CARBON_CLASS;
        $carbon::resetHolidays();
        $carbon::setHolidaysRegion('cn-national');

        self::assertFalse($carbon::parse('2019-02-03')->isHoliday());
        self::assertTrue($carbon::parse('2019-02-04')->isHoliday());
        self::assertTrue($carbon::parse('2019-02-10')->isHoliday());
        self::assertFalse($carbon::parse('2019-02-11')->isHoliday());

        self::assertFalse($carbon::parse('2019-04-04')->isHoliday());
        self::assertTrue($carbon::parse('2019-04-05')->isHoliday());
        self::assertFalse($carbon::parse('2019-04-06')->isHoliday());

        self::assertFalse($carbon::parse('2019-06-06')->isHoliday());
        self::assertTrue($carbon::parse('2019-06-07')->isHoliday());
        self::assertFalse($carbon::parse('2019-06-08')->isHoliday());

        self::assertFalse($carbon::parse('2020-01-23')->isHoliday());
        self::assertTrue($carbon::parse('2020-01-24')->isHoliday());
        self::assertTrue($carbon::parse('2020-01-25')->isHoliday());
        self::assertTrue($carbon::parse('2020-01-26')->isHoliday());
        self::assertTrue($carbon::parse('2020-01-27')->isHoliday());
        self::assertTrue($carbon::parse('2020-01-28')->isHoliday());
        self::assertTrue($carbon::parse('2020-01-29')->isHoliday());
        self::assertTrue($carbon::parse('2020-01-30')->isHoliday());
        self::assertFalse($carbon::parse('2020-12-31')->isHoliday());

        self::assertTrue($carbon::parse('2021-01-01')->isHoliday());
        self::assertTrue($carbon::parse('2021-01-02')->isHoliday());
        self::assertTrue($carbon::parse('2021-01-03')->isHoliday());
        self::assertFalse($carbon::parse('2021-01-04')->isHoliday());
        self::assertFalse($carbon::parse('2021-02-10')->isHoliday());
        self::assertTrue($carbon::parse('2021-02-11')->isHoliday());
        self::assertTrue($carbon::parse('2021-02-12')->isHoliday());
        self::assertTrue($carbon::parse('2021-02-13')->isHoliday());
        self::assertTrue($carbon::parse('2021-02-14')->isHoliday());
        self::assertTrue($carbon::parse('2021-02-15')->isHoliday());
        self::assertTrue($carbon::parse('2021-02-16')->isHoliday());
        self::assertTrue($carbon::parse('2021-02-17')->isHoliday());
        self::assertFalse($carbon::parse('2021-02-18')->isHoliday());
        self::assertFalse($carbon::parse('2021-04-02')->isHoliday());
        self::assertTrue($carbon::parse('2021-04-05')->isHoliday());
        self::assertFalse($carbon::parse('2021-04-06')->isHoliday());
        self::assertTrue($carbon::parse('2021-05-01')->isHoliday());
        self::assertTrue($carbon::parse('2021-05-02')->isHoliday());
        self::assertTrue($carbon::parse('2021-05-03')->isHoliday());
        self::assertTrue($carbon::parse('2021-05-04')->isHoliday());
        self::assertTrue($carbon::parse('2021-05-05')->isHoliday());
        self::assertTrue($carbon::parse('2021-06-14')->isHoliday());
        self::assertTrue($carbon::parse('2021-09-21')->isHoliday());
        self::assertTrue($carbon::parse('2021-10-01')->isHoliday());
        self::assertTrue($carbon::parse('2021-10-02')->isHoliday());
        self::assertTrue($carbon::parse('2021-10-03')->isHoliday());
        self::assertTrue($carbon::parse('2021-10-04')->isHoliday());
        self::assertTrue($carbon::parse('2021-10-05')->isHoliday());
        self::assertTrue($carbon::parse('2021-10-06')->isHoliday());
        self::assertTrue($carbon::parse('2021-10-07')->isHoliday());
    }

    public function testExtraWorkDays(): void
    {
        $carbon = static::CARBON_CLASS;
        $carbon::resetHolidays();
        $carbon::setHolidaysRegion('cn-national');

        self::assertTrue($carbon::parse('2021-02-07')->isBusinessDay());
    }
}
