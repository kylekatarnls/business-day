<?php

namespace Tests\Cmixin\Holidays;

use Cmixin\BusinessDay;
use PHPUnit\Framework\TestCase;

class SlTest extends TestCase
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
        $carbon::setHolidaysRegion('sl-national');
        $holidays = include __DIR__.'/../../../src/Cmixin/Holidays/sl-national.php';

        $randomYear = rand(1991, 2028);
        $randomHolidays = array();
        foreach ($holidays as $holiday) {
            if (is_callable($holiday)) {
                $randomHolidays[] = $holiday($randomYear);
            } elseif (is_string($holiday)) {
                $randomHolidays[] = $holiday;
            }
        }

        $date = $carbon::parse("$randomYear-01-01");
        while ($date->format('Y') == $randomYear) {
            if (in_array($date->format('d/m'), $randomHolidays)) {
                self::assertTrue($date->isHoliday(), sprintf('Date %s should be holiday!', $date->format('Y-m-d')));
            } else {
                self::assertFalse($date->isHoliday(), sprintf('Date %s should not be holiday!', $date->format('Y-m-d')));
            }

            $date->modify('+1 day');
        }
    }
}
