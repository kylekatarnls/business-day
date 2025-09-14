<?php

declare(strict_types=1);

namespace Cmixin\BusinessDay\Util;

use DateTime;
use InvalidArgumentException;

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
        $value = (int) $dateTime->format('Y');
        $subConditions = $this->getYearConditions($condition);

        if ($subConditions === []) {
            return $this->year === $value;
        }

        $condition = null;

        foreach ($subConditions as $subCondition) {
            if (!$this->matchYearSubCondition($value, $subCondition)) {
                return false;
            }
        }

        return true;
    }

    private function matchYearSubCondition(int $value, array $match): bool
    {
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

    private function getYearConditions(?string $condition): array
    {
        if ($condition === null) {
            return [];
        }

        $subConditions = explode(' and ', $condition);
        $conditionMatches = [];

        foreach ($subConditions as $subCondition) {
            if (preg_match(
                '/^\s*year(?:\s*%\s*(?<modulo>\d+))?\s*(?<operator>>=?|<=?|={1,3}|!={1,2}|<>)\s*(?<expected>\d+)/',
                $subCondition,
                $match
            )) {
                $conditionMatches[] = $match;
            }
        }

        $finalCount = \count($conditionMatches);

        if ($finalCount === 0 || $finalCount === \count($subConditions)) {
            return $conditionMatches;
        }

        throw new InvalidArgumentException(
            'Mixed conditions are not supported for now, they must all target the same unit (for example: year)'
        );
    }
}
