<?php

namespace Tests\Cmixin\Holidays;

use Cmixin\BusinessDay;
use PHPUnit\Framework\TestCase;

class JpTest extends TestCase
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
        $carbon::setHolidaysRegion('jp-national');
        $carbon::addHolidays('jp-national', array(
            'june-solstice' => '= June solstice of +09:00',
        ));

        self::assertFalse($carbon::parse('2019-03-20')->isHoliday());
        self::assertTrue($carbon::parse('2019-03-21')->isHoliday());
        self::assertFalse($carbon::parse('2019-03-22')->isHoliday());

        self::assertFalse($carbon::parse('2019-06-21')->isHoliday());
        self::assertTrue($carbon::parse('2019-06-22')->isHoliday());
        self::assertFalse($carbon::parse('2019-06-23')->isHoliday());

        self::assertFalse($carbon::parse('2019-09-15')->isHoliday());
        self::assertTrue($carbon::parse('2019-09-16')->isHoliday());
        self::assertFalse($carbon::parse('2019-09-17')->isHoliday());

        self::assertFalse($carbon::parse('2019-09-22')->isHoliday());
        self::assertTrue($carbon::parse('2019-09-23')->isHoliday());
        self::assertFalse($carbon::parse('2019-09-24')->isHoliday());

        self::assertFalse($carbon::parse('2020-09-20')->isHoliday());
        self::assertTrue($carbon::parse('2020-09-21')->isHoliday());
        self::assertTrue($carbon::parse('2020-09-22')->isHoliday());
        self::assertTrue($carbon::parse('2020-09-23')->isHoliday());
        self::assertFalse($carbon::parse('2020-09-24')->isHoliday());
    }
}
