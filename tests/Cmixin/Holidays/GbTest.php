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

        // 2021 year, when new year is a Friday
        self::assertTrue($carbon::parse('2021-01-01')->isHoliday()); // New Year
        self::assertSame('New Year', $carbon::parse('2021-01-01')->getHolidayName());

        // 2022 year, when new year is a Saturday
        self::assertFalse($carbon::parse('2022-01-01')->isHoliday()); // New Year
        self::assertFalse($carbon::parse('2022-01-02')->isHoliday()); // Sunday
        self::assertTrue($carbon::parse('2022-01-03')->isHoliday()); // New Year (Substitute)
        self::assertSame('New Year', $carbon::parse('2022-01-03')->getHolidayName());

        // 2023 year, when new year is a Sunday
        self::assertFalse($carbon::parse('2023-01-01')->isHoliday()); // New Year
        self::assertTrue($carbon::parse('2023-01-02')->isHoliday()); // New Year (Substitute)
        self::assertSame('New Year', $carbon::parse('2023-01-02')->getHolidayName());

        $carbon::setHolidaysRegion('gb-engwales');

        // 2019 year
        self::assertTrue($carbon::parse('2019-01-01')->isHoliday()); // New Year
        self::assertSame('New Year', $carbon::parse('2019-01-01')->getHolidayName());
        self::assertTrue($carbon::parse('2019-04-19')->isHoliday()); // Good Friday
        self::assertSame('Good Friday', $carbon::parse('2019-04-19')->getHolidayName());
        self::assertTrue($carbon::parse('2019-04-22')->isHoliday()); // Easter Monday
        self::assertSame('Easter Monday', $carbon::parse('2019-04-22')->getHolidayName());
        self::assertTrue($carbon::parse('2019-05-06')->isHoliday()); // Early May Bank Holiday
        self::assertSame('Early May Bank Holiday', $carbon::parse('2019-05-06')->getHolidayName());
        self::assertTrue($carbon::parse('2019-05-27')->isHoliday()); // Spring Bank Holiday
        self::assertSame('Spring Bank Holiday', $carbon::parse('2019-05-27')->getHolidayName());
        self::assertTrue($carbon::parse('2019-08-26')->isHoliday()); // Summer Bank Holiday
        self::assertSame('Summer Bank Holiday', $carbon::parse('2019-08-26')->getHolidayName());
        self::assertTrue($carbon::parse('2019-12-25')->isHoliday()); // Christmas
        self::assertSame('Christmas', $carbon::parse('2019-12-25')->getHolidayName());
        self::assertTrue($carbon::parse('2019-12-26')->isHoliday()); // Boxing Day
        self::assertSame('Boxing Day', $carbon::parse('2019-12-26')->getHolidayName());

        // 2020 year
        self::assertTrue($carbon::parse('2020-01-01')->isHoliday()); // New Year
        self::assertSame('New Year', $carbon::parse('2020-01-01')->getHolidayName());
        self::assertTrue($carbon::parse('2020-04-10')->isHoliday()); // Good Friday
        self::assertSame('Good Friday', $carbon::parse('2020-04-10')->getHolidayName());
        self::assertTrue($carbon::parse('2020-04-13')->isHoliday()); // Easter Monday
        self::assertSame('Easter Monday', $carbon::parse('2020-04-13')->getHolidayName());
        self::assertTrue($carbon::parse('2020-05-04')->isHoliday()); // Early May Bank Holiday
        self::assertSame('Early May Bank Holiday', $carbon::parse('2020-05-04')->getHolidayName());
        self::assertTrue($carbon::parse('2020-05-25')->isHoliday()); // Spring Bank Holiday
        self::assertSame('Spring Bank Holiday', $carbon::parse('2020-05-25')->getHolidayName());
        self::assertTrue($carbon::parse('2020-08-31')->isHoliday()); // Summer Bank Holiday
        self::assertSame('Summer Bank Holiday', $carbon::parse('2020-08-31')->getHolidayName());
        self::assertTrue($carbon::parse('2020-12-25')->isHoliday()); // Christmas
        self::assertSame('Christmas', $carbon::parse('2020-12-25')->getHolidayName());
        self::assertFalse($carbon::parse('2020-12-26')->isHoliday()); // Boxing Day
        self::assertTrue($carbon::parse('2020-12-28')->isHoliday()); // Boxing Day (Substitute)
        self::assertSame('Boxing Day', $carbon::parse('2020-12-28')->getHolidayName());

        $carbon::setHolidaysRegion('gb-nireland');

        // 2019 year
        self::assertTrue($carbon::parse('2019-01-01')->isHoliday()); // New Year
        self::assertSame('New Year', $carbon::parse('2019-01-01')->getHolidayName());
        self::assertFalse($carbon::parse('2019-03-17')->isHoliday()); // St Patrick's Day
        self::assertTrue($carbon::parse('2019-03-18')->isHoliday()); // St Patrick's Day (Substitute)
        self::assertSame('St Patrick\'s Day', $carbon::parse('2019-03-18')->getHolidayName());
        self::assertTrue($carbon::parse('2019-04-19')->isHoliday()); // Good Friday
        self::assertSame('Good Friday', $carbon::parse('2019-04-19')->getHolidayName());
        self::assertTrue($carbon::parse('2019-04-22')->isHoliday()); // Easter Monday
        self::assertSame('Easter Monday', $carbon::parse('2019-04-22')->getHolidayName());
        self::assertTrue($carbon::parse('2019-05-06')->isHoliday()); // Early May Bank Holiday
        self::assertSame('Early May Bank Holiday', $carbon::parse('2019-05-06')->getHolidayName());
        self::assertTrue($carbon::parse('2019-05-27')->isHoliday()); // Spring Bank Holiday
        self::assertSame('Spring Bank Holiday', $carbon::parse('2019-05-27')->getHolidayName());
        self::assertTrue($carbon::parse('2019-07-12')->isHoliday()); // Battle of the Boyne (Orangemen's Day)
        self::assertSame('Battle of the Boyne (Orangemen\'s Day)', $carbon::parse('2019-07-12')->getHolidayName());
        self::assertTrue($carbon::parse('2019-08-26')->isHoliday()); // Summer Bank Holiday
        self::assertSame('Summer Bank Holiday', $carbon::parse('2019-08-26')->getHolidayName());
        self::assertTrue($carbon::parse('2019-12-25')->isHoliday()); // Christmas
        self::assertSame('Christmas', $carbon::parse('2019-12-25')->getHolidayName());
        self::assertTrue($carbon::parse('2019-12-26')->isHoliday()); // Boxing Day
        self::assertSame('Boxing Day', $carbon::parse('2019-12-26')->getHolidayName());

        // 2020 year
        self::assertTrue($carbon::parse('2020-01-01')->isHoliday()); // New Year
        self::assertSame('New Year', $carbon::parse('2020-01-01')->getHolidayName());
        self::assertTrue($carbon::parse('2020-03-17')->isHoliday()); // St Patrick's Day
        self::assertSame('St Patrick\'s Day', $carbon::parse('2020-03-17')->getHolidayName());
        self::assertTrue($carbon::parse('2020-04-10')->isHoliday()); // Good Friday
        self::assertSame('Good Friday', $carbon::parse('2020-04-10')->getHolidayName());
        self::assertTrue($carbon::parse('2020-04-13')->isHoliday()); // Easter Monday
        self::assertSame('Easter Monday', $carbon::parse('2020-04-13')->getHolidayName());
        self::assertTrue($carbon::parse('2020-05-04')->isHoliday()); // Early May Bank Holiday
        self::assertSame('Early May Bank Holiday', $carbon::parse('2020-05-04')->getHolidayName());
        self::assertTrue($carbon::parse('2020-05-25')->isHoliday()); // Spring Bank Holiday
        self::assertSame('Spring Bank Holiday', $carbon::parse('2020-05-25')->getHolidayName());
        self::assertFalse($carbon::parse('2020-07-12')->isHoliday()); // Battle of the Boyne (Orangemen's Day)
        self::assertTrue($carbon::parse('2020-07-13')->isHoliday()); // Battle of the Boyne (Orangemen's Day) (Substitute)
        self::assertSame('Battle of the Boyne (Orangemen\'s Day)', $carbon::parse('2020-07-13')->getHolidayName());
        self::assertTrue($carbon::parse('2020-08-31')->isHoliday()); // Summer Bank Holiday
        self::assertSame('Summer Bank Holiday', $carbon::parse('2020-08-31')->getHolidayName());
        self::assertTrue($carbon::parse('2020-12-25')->isHoliday()); // Christmas
        self::assertSame('Christmas', $carbon::parse('2020-12-25')->getHolidayName());
        self::assertFalse($carbon::parse('2020-12-26')->isHoliday()); // Boxing Day
        self::assertTrue($carbon::parse('2020-12-28')->isHoliday()); // Boxing Day (Substitute)
        self::assertSame('Boxing Day', $carbon::parse('2020-12-28')->getHolidayName());

        $carbon::setHolidaysRegion('gb-scotland');

        // 2019 year
        self::assertTrue($carbon::parse('2019-01-01')->isHoliday()); // New Year
        self::assertSame('New Year', $carbon::parse('2019-01-01')->getHolidayName());
        self::assertTrue($carbon::parse('2019-01-02')->isHoliday()); // 2nd January
        self::assertSame('2nd January', $carbon::parse('2019-01-02')->getHolidayName());
        self::assertTrue($carbon::parse('2019-04-19')->isHoliday()); // Good Friday
        self::assertSame('Good Friday', $carbon::parse('2019-04-19')->getHolidayName());
        self::assertTrue($carbon::parse('2019-05-06')->isHoliday()); // Early May Bank Holiday
        self::assertSame('Early May Bank Holiday', $carbon::parse('2019-05-06')->getHolidayName());
        self::assertTrue($carbon::parse('2019-05-27')->isHoliday()); // Spring Bank Holiday
        self::assertSame('Spring Bank Holiday', $carbon::parse('2019-05-27')->getHolidayName());
        self::assertTrue($carbon::parse('2019-08-05')->isHoliday()); // Summer Bank Holiday
        self::assertSame('Summer Bank Holiday', $carbon::parse('2019-08-05')->getHolidayName());
        self::assertFalse($carbon::parse('2019-11-30')->isHoliday()); // St Andrew's Day
        self::assertTrue($carbon::parse('2019-12-02')->isHoliday()); // St Andrew's Day (Substitute)
        self::assertSame('St Andrew\'s Day', $carbon::parse('2019-12-02')->getHolidayName());
        self::assertTrue($carbon::parse('2019-12-25')->isHoliday()); // Christmas
        self::assertSame('Christmas', $carbon::parse('2019-12-25')->getHolidayName());
        self::assertTrue($carbon::parse('2019-12-26')->isHoliday()); // Boxing Day
        self::assertSame('Boxing Day', $carbon::parse('2019-12-26')->getHolidayName());

        // 2020 year
        self::assertTrue($carbon::parse('2020-01-01')->isHoliday()); // New Year
        self::assertSame('New Year', $carbon::parse('2020-01-01')->getHolidayName());
        self::assertTrue($carbon::parse('2020-01-02')->isHoliday()); // 2nd January
        self::assertSame('2nd January', $carbon::parse('2020-01-02')->getHolidayName());
        self::assertTrue($carbon::parse('2020-04-10')->isHoliday()); // Good Friday
        self::assertSame('Good Friday', $carbon::parse('2020-04-10')->getHolidayName());
        self::assertTrue($carbon::parse('2020-05-04')->isHoliday()); // Early May Bank Holiday
        self::assertSame('Early May Bank Holiday', $carbon::parse('2020-05-04')->getHolidayName());
        self::assertTrue($carbon::parse('2020-05-25')->isHoliday()); // Spring Bank Holiday
        self::assertSame('Spring Bank Holiday', $carbon::parse('2020-05-25')->getHolidayName());
        self::assertTrue($carbon::parse('2020-08-03')->isHoliday()); // Summer Bank Holiday
        self::assertSame('Summer Bank Holiday', $carbon::parse('2020-08-03')->getHolidayName());
        self::assertTrue($carbon::parse('2020-11-30')->isHoliday()); // St Andrew's Day
        self::assertSame('St Andrew\'s Day', $carbon::parse('2020-11-30')->getHolidayName());
        self::assertTrue($carbon::parse('2020-12-25')->isHoliday()); // Christmas
        self::assertSame('Christmas', $carbon::parse('2020-12-25')->getHolidayName());
        self::assertFalse($carbon::parse('2020-12-26')->isHoliday()); // Boxing Day
        self::assertTrue($carbon::parse('2020-12-28')->isHoliday()); // Boxing Day (Substitute)
        self::assertSame('Boxing Day', $carbon::parse('2020-12-28')->getHolidayName());

        // 2021 year, when new year is a Friday
        self::assertTrue($carbon::parse('2021-01-01')->isHoliday()); // New Year
        self::assertSame('New Year', $carbon::parse('2021-01-01')->getHolidayName());
        self::assertFalse($carbon::parse('2021-01-02')->isHoliday()); // 2nd January
        self::assertFalse($carbon::parse('2021-01-03')->isHoliday()); // Simple Sunday
        self::assertTrue($carbon::parse('2021-01-04')->isHoliday()); // 2nd January (Substitute)
        self::assertSame('2nd January', $carbon::parse('2021-01-04')->getHolidayName());

        // 2022 year, when new year is a Saturday
        self::assertFalse($carbon::parse('2022-01-01')->isHoliday()); // New Year
        self::assertFalse($carbon::parse('2022-01-02')->isHoliday()); // 2nd January
        self::assertTrue($carbon::parse('2022-01-03')->isHoliday()); // New Year (Substitute)
        self::assertSame('New Year', $carbon::parse('2022-01-03')->getHolidayName());
        self::assertTrue($carbon::parse('2022-01-04')->isHoliday()); // 2nd January (Substitute)
        self::assertSame('2nd January', $carbon::parse('2022-01-04')->getHolidayName());

        // 2023 year, when new year is a Sunday
        self::assertFalse($carbon::parse('2023-01-01')->isHoliday()); // New Year
        self::assertTrue($carbon::parse('2023-01-02')->isHoliday()); // New Year (Substitute)
        self::assertSame('New Year', $carbon::parse('2023-01-02')->getHolidayName());
        self::assertTrue($carbon::parse('2023-01-03')->isHoliday()); // 2nd January (Substitute)
        self::assertSame('2nd January', $carbon::parse('2023-01-03')->getHolidayName());
        self::assertFalse($carbon::parse('2023-01-04')->isHoliday()); // Simple Wednesday
    }
}
