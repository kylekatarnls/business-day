<?php

declare(strict_types=1);

namespace Cmixin\BusinessDay\Util;

use DateTime;

trait YearCondition
{
    /**
     * @param DateTime    $dateTime
     * @param string|null $condition
     *
     * @return bool
     */
    protected function matchYearCondition($dateTime, ?string &$condition): bool
    {
        if (!$condition || !preg_match(
            '/^\s*year(?:\s*%\s*(?<modulo>\d+))?\s*(?<operator>>=?|<=?|={1,3}|!={1,2}|<>)\s*(?<expected>\d+)/',
            $condition,
            $match
        )) {
            return true;
        }

        $condition = null;
        $value = (int) $dateTime->format('Y');
        $expected = (int) $match['expected'];

        if (!empty($match['modulo'])) {
            $value %= $match['modulo'];
        }

        switch ($match['operator']) {
            case '>':
                return $value > $expected;

            case '>=':
                return $value >= $expected;

            case '<':
                return $value < $expected;

            case '<=':
                return $value <= $expected;

            case '!=':
            case '!==':
            case '<>':
                return $value !== $expected;

            default:
                return $value === $expected;
        }
    }
}
