<?php

namespace Tests\Cmixin;

use Carbon\Carbon;
use Cmixin\BusinessDay;
use PHPUnit\Framework\TestCase;

class ListsTest extends TestCase
{
    const CARBON_CLASS = 'Carbon\Carbon';

    protected function setUp(): void
    {
        BusinessDay::enable(static::CARBON_CLASS);
    }

    /**
     * @dataProvider getHolidaysLists
     */
    public function testListsValidity($list)
    {
        Carbon::setHolidaysRegion($list);

        $holidays = Carbon::getYearHolidays();

        self::assertFalse(empty($holidays), $list.': getYearHolidays should returns non-empty array');

        $allCarbon = true;

        foreach ($holidays as $holiday) {
            if (!($holiday instanceof Carbon)) {
                $allCarbon = false;

                break;
            }
        }

        self::assertTrue($allCarbon, $list.': getYearHolidays should returns only Carbon objects');
    }

    public static function getHolidaysLists(): array
    {
        $lists = [];

        foreach (glob(__DIR__.'/../../src/Cmixin/Holidays/*.php') as $file) {
            $lists[] = [substr(basename($file), 0, -4)];
        }

        return $lists;
    }

    /**
     * @dataProvider getHolidaysNames
     */
    public function testNamesValidity($name)
    {
        $translations = Carbon::getHolidayNamesDictionary($name);

        self::assertFalse(empty($translations), $name.' names translations should returns non-empty array');

        $allStrings = true;

        foreach ($translations as $translation) {
            if (!is_string($translation)) {
                $allStrings = false;

                break;
            }
        }

        self::assertTrue($allStrings, $name.' names translations should returns only string entries');
    }

    public static function getHolidaysNames()
    {
        $names = [];

        foreach (glob(__DIR__.'/../../src/Cmixin/HolidayNames/*.php') as $file) {
            $names[] = [substr(basename($file), 0, -4)];
        }

        return $names;
    }

    public function testNamesInvalidity()
    {
        $translations = Carbon::getHolidayNamesDictionary('does-not-exists');

        self::assertSame('Christmas', $translations['christmas']);

        $translations = Carbon::getHolidayNamesDictionary('does-not-exists-either');

        self::assertSame('Christmas', $translations['christmas']);
    }

    public function testGetYearHolidays()
    {
        Carbon::setHolidaysRegion('us');

        $days = [];

        foreach (Carbon::getYearHolidays(2020) as $holiday) {
            $days[] = $holiday->getHolidayName().': '.$holiday->format('l, F j, Y');
        }

        self::assertSame([
            'New Year: Wednesday, January 1, 2020',
            'Martin Luther King Jr. Day: Monday, January 20, 2020',
            'Washington’s Birthday: Monday, February 17, 2020',
            'Memorial Day: Monday, May 25, 2020',
            'Independence Day: Monday, July 6, 2020',
            'Labor Day: Monday, September 7, 2020',
            'Columbus Day: Monday, October 12, 2020',
            'Day of the Veterans: Wednesday, November 11, 2020',
            'Thanksgiving: Thursday, November 26, 2020',
            'Christmas: Friday, December 25, 2020',
        ], $days);
    }

    public function testGoldenWeek()
    {
        Carbon::setHolidaysRegion('cn');

        self::assertTrue(Carbon::parse('2021-10-07')->isHoliday());
        self::assertTrue(Carbon::parse('2000-10-07')->isHoliday());
        self::assertFalse(Carbon::parse('1999-10-07')->isHoliday());

        Carbon::addHolidays('cn-national', [
            '= 06-06 if year > 2022',
            '= 06-07 if year = 2022',
            '= 06-08 if year < 2022',
            '= 06-09 if year <= 2022',
            '= 06-10 if year <> 2022',
        ]);

        $holidays = [
            2021 => [8, 9, 10],
            2022 => [7, 9],
            2023 => [6, 10],
        ];

        foreach ($holidays as $year => $days) {
            for ($day = 6; $day <= 10; $day++) {
                $holiday = in_array($day, $days, true);

                self::assertSame(
                    $holiday,
                    Carbon::parse("$year-6-$day")->isHoliday(),
                    "June {$day}th $year should be a ".($holiday ? 'holiday' : 'business day')
                );
            }
        }
    }
}
