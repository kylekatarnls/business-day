<?php

namespace Cmixin\BusinessDay\Calendar;

/**
 * @internal
 */
interface AlternativeCalendarInterface
{
    public function getDate($year, $month, $day);
}
