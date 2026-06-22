<?php

namespace Tests\Fixture;

use Cmixin\BusinessDay\Calculator\HolidayCalculator;
use Cmixin\BusinessDay\Calendar\MissingCalendarExtensionException;

final class CalculatorTester extends HolidayCalculator
{
    protected function parseHoliday($holiday, &$dateTime = null, $key = null): ?string
    {
        throw MissingCalendarExtensionException::forFunction('abc');
    }

    public static function check(): bool
    {
        $holidays = [];
        $tester = new self(2026, 'string', 'us-national', $holidays);

        return $tester->doCheck();
    }

    public function doCheck(): bool
    {
        try {
            return $this->getHolidayDate('', '');
        } catch (MissingCalendarExtensionException $exception) {
            return true;
        }
    }
}
