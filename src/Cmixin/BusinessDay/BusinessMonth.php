<?php

namespace Cmixin\BusinessDay;

/**
 * @internal
 */
class BusinessMonth
{
    /**
     * @var \Carbon\Carbon|\Cmixin\BusinessDay
     */
    protected $start;

    /**
     * @var \Carbon\Carbon|\Cmixin\BusinessDay
     */
    protected $end;

    /**
     * @param \Carbon\Carbon|\Cmixin\BusinessDay|string $date
     * @param string                                    $carbonClass
     */
    public function __construct($date, $carbonClass)
    {
        if (is_string($date)) {
            $date = $carbonClass::parse($date);
        } elseif (!is_a($date, $carbonClass)) {
            $date = $carbonClass::instance($date ?: $carbonClass::now());
        }

        $this->start = $date->copy()->startOfMonth();
        $this->end = $date->copy()->endOfMonth();
    }

    /**
     * @return \Carbon\Carbon|\Cmixin\BusinessDay
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return \Carbon\Carbon|\Cmixin\BusinessDay
     */
    public function getEnd()
    {
        return $this->end;
    }
}
