<?php

namespace Cmixin\BusinessDay\Calendar;

use BadFunctionCallException;

final class MissingCalendarExtensionException extends BadFunctionCallException
{
    public static function forFunction(string $function)
    {
        return new self(
            $function.' function is not available on your system, '.
            'you need either to install PHP calendar extension or a polyfill'.
            (
                in_array($function, ['easter_date', 'easter_days'], true)
                    ? " such as:\ncomposer require roukmoute/polyfill-calendar"
                    : '.'
            )
        );
    }
}
