<?php

namespace Tests\Cmixin;

use Carbon\Carbon;
use Cmixin\BusinessDay;
use PHPUnit\Framework\TestCase;

class ListsTest extends TestCase
{
    const CARBON_CLASS = 'Carbon\Carbon';

    protected function setUp()
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
        $lists = array();

        foreach (glob(__DIR__.'/../../src/Cmixin/Holidays/*.php') as $file) {
            $lists[] = array(substr(basename($file), 0, -4));
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
        $names = array();

        foreach (glob(__DIR__.'/../../src/Cmixin/HolidayNames/*.php') as $file) {
            $names[] = array(substr(basename($file), 0, -4));
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
}
