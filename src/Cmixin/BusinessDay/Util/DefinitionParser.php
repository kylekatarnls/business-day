<?php

declare(strict_types=1);

namespace Cmixin\BusinessDay\Util;

use Carbon\CarbonInterface;

class DefinitionParser
{
    /** @var array */
    private $arguments;

    public function __construct(array $arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * @param string $carbonClass
     * @psalm-param class-string<CarbonInterface> $carbonClass
     */
    public function applyTo(string $carbonClass): void
    {
        if (!isset($this->arguments[1])) {
            return;
        }

        $region = $this->arguments[1];
        $with = $this->arguments[2] ?? null;
        $without = $this->arguments[3] ?? null;

        if (is_array($region)) {
            ['region' => $region, 'with' => $with, 'without' => $without] = $region;
        }

        if (!is_string($region)) {
            throw new InvalidArgumentException(
                'Region must be a string, '.
                (is_object($region) ? get_class($region) : gettype($region)).' provided.'
            );
        }

        $carbonClass::setHolidaysRegion($region);

        if ($with || $without) {
            $carbonClass::addHolidays($carbonClass::getHolidaysRegion(), $with, $without);
        }
    }
}
