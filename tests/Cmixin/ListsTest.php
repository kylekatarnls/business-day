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

    public function getHolidaysLists()
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

    public function getHolidaysNames()
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

        foreach (Carbon::getYearHolidays(2020) as $id => $holiday) {
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
            'Day of the veterans: Wednesday, November 11, 2020',
            'Thanksgiving: Thursday, November 26, 2020',
            'Christmas: Friday, December 25, 2020',
        ], $days);
    }
}
