<?php

namespace Tests\Cmixin;

use Cmixin\BusinessDay;
use Cmixin\BusinessDay\Calendar\CalendarExtensionChecker;
use Cmixin\BusinessDay\Calendar\MissingCalendarExtensionException;
use PHPUnit\Framework\TestCase;

final class CalendarExtensionCheckerTest extends TestCase
{
    public function testMissingCalendarExtensionException(): void
    {
        self::assertSame(
            'jewishtojd function is not available on your system, '.
            'you need either to install PHP calendar extension or a polyfill.'.
            "\n\nOr alternatively, skip all holidays using such functions by ".
            'calling explicitly '.BusinessDay::class.'::skipMissingCalendarExtensionException()',
            MissingCalendarExtensionException::forFunction('jewishtojd')->getMessage()
        );
        self::assertSame(
            'easter_days function is not available on your system, '.
            "you need either to install PHP calendar extension or a polyfill such as:\n".
            'composer require roukmoute/polyfill-calendar'.
            "\n\nOr alternatively, skip all holidays using such functions by ".
            'calling explicitly '.BusinessDay::class.'::skipMissingCalendarExtensionException()',
            MissingCalendarExtensionException::forFunction('easter_days')->getMessage()
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
