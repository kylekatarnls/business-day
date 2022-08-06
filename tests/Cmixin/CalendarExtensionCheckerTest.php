<?php

namespace Tests\Cmixin;

use Cmixin\BusinessDay\Calendar\CalendarExtensionChecker;
use Cmixin\BusinessDay\Calendar\MissingCalendarExtensionException;
use PHPUnit\Framework\TestCase;

class CalendarExtensionCheckerTest extends TestCase
{
    public function testMissingCalendarExtensionException(): void
    {
        self::assertSame(
            'foobar function is not available on your system, '.
            "you need either to install PHP calendar extension or a polyfill such as:\n".
            'composer require roukmoute/polyfill-calendar',
            MissingCalendarExtensionException::forFunction('foobar')->getMessage()
        );
    }

    public function testCalendarExtensionChecker(): void
    {
        self::expectExceptionObject(MissingCalendarExtensionException::forFunction('does_not_exists'));

        (new CalendarExtensionChecker())->requireFunctions(
            ['strtolower', 'does_not_exists', 'does_not_exists_neither']
        );
    }
}
