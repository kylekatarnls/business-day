<?php

namespace Cmixin\BusinessDay;

use Exception;

// @codeCoverageIgnoreStart
class Emulator
{
    public static function getClass(Exception $exception)
    {
        foreach ($exception->getTrace() as $step) {
            if (isset($step['class']) && isset($step['function']) && $step['function'] !== '__call') {
                return $step['class'];
            }
        }

        return 'Carbon\Carbon';
    }
}
// @codeCoverageIgnoreEnd
