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
            '/^\s*year\s*(?<operator>>=?|<=?|={1,3}|!={1,2}|<>)\s*(?<year>\d+)/',
            $condition,
            $match
        )) {
            return true;
        }

        $condition = null;

        switch ($match['operator']) {
            case '>':
                return $dateTime->format('Y') > $match['year'];

            case '>=':
                return $dateTime->format('Y') >= $match['year'];

            case '<':
                return $dateTime->format('Y') < $match['year'];

            case '<=':
                return $dateTime->format('Y') <= $match['year'];

            case '!=':
            case '!==':
            case '<>':
                return $dateTime->format('Y') !== $match['year'];

            default:
                return $dateTime->format('Y') === $match['year'];
        }
    }
}
