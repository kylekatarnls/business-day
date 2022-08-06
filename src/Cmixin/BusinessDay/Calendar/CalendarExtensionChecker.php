<?php

namespace Cmixin\BusinessDay\Calendar;

final class CalendarExtensionChecker
{
    public function requireFunction(string $function): void
    {
        if (!function_exists($function)) {
            throw MissingCalendarExtensionException::forFunction($function);
        }
    }

    public function requireFunctions(iterable $functions): void
    {
        foreach ($functions as $function) {
            $this->requireFunction($function);
        }
    }
}
