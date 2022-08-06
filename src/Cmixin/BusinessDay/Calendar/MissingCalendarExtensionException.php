<?php

namespace Cmixin\BusinessDay\Calendar;

use BadFunctionCallException;

final class MissingCalendarExtensionException extends BadFunctionCallException
{
    public static function forFunction(string $function)
    {
        return new self(
            $function.' function is not available on your system, '.
            "you need either to install PHP calendar extension or a polyfill such as:\n".
            'composer require roukmoute/polyfill-calendar'
        );
    }
}
