<?php

namespace Cmixin\BusinessDay\Calendar;

use BadFunctionCallException;
use Cmixin\BusinessDay;

final class MissingCalendarExtensionException extends BadFunctionCallException
{
    public static function forFunction(string $function): self
    {
        return new self(
            $function.' function is not available on your system, '.
            'you need either to install PHP calendar extension or a polyfill '.
            "such as:\ncomposer require roukmoute/polyfill-calendar\n\n".
            'Or alternatively, skip all holidays using such functions by '.
            'calling explicitly '.BusinessDay::class.'::skipMissingCalendarExtensionException()'
        );
    }
}
