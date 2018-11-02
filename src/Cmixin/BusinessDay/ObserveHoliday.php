<?php

namespace Cmixin\BusinessDay;

use Carbon\Carbon;
use Cmixin\BusinessDay;

class ObserveHoliday extends Holiday
{
    const OBSERVE_ALL_HOLIDAYS = 'all';
    /**
     * @var array
     */
    public $observedHolidays = array();

    /**
     * @var string
     */
    public $observedHolidaysZone = 'default';

    /**
     * Set the selected zone for observed holidays.
     *
     * @return \Closure
     */
    public function setObservedHolidaysZone()
    {
        $mixin = $this;

        return function ($zone) use ($mixin) {
            $mixin->observedHolidaysZone = $zone;

            return isset($this) ? $this : null;
        };
    }

    /**
     * Get the selected zone for observed holidays.
     *
     * @return \Closure
     */
    public function getObservedHolidaysZone()
    {
        $mixin = $this;

        return function () use ($mixin) {
            return $mixin->observedHolidaysZone;
        };
    }

    /**
     * Set a holiday as observed in the selected zone.
     *
     * @return \Closure
     */
    public function observeHoliday($defaultValue = true, $defaultDay = null)
    {
        $mixin = $this;
        $allHolidays = static::OBSERVE_ALL_HOLIDAYS;

        return function ($day = null, $value = null) use ($mixin, $defaultValue, $defaultDay, $allHolidays) {
            if (!$day && $defaultDay) {
                $day = $defaultDay;
            }
            $days = (array) $day;
            foreach ($days as $day) {
                if (!is_string($day)) {
                    throw new \InvalidArgumentException(
                        'You must pass holiday names as a string or "'.$allHolidays.'".'
                    );
                }
                $zone = $mixin->observedHolidaysZone;
                if ($day === $allHolidays || !isset($mixin->observedHolidays[$zone])) {
                    $mixin->observedHolidays[$zone] = array();
                }

                $mixin->observedHolidays[$zone][$day] = $value === null ? $defaultValue : $value;
            }

            return isset($this) ? $this : null;
        };
    }

    /**
     * Set a holiday as not observed in the selected zone.
     *
     * @return \Closure
     */
    public function unobserveHoliday()
    {
        return $this->observeHoliday(false);
    }

    /**
     * Set a holiday as observed in the selected zone.
     *
     * @return \Closure
     */
    public function observeHolidays()
    {
        return $this->observeHoliday(true);
    }

    /**
     * Set a holiday as not observed in the selected zone.
     *
     * @return \Closure
     */
    public function unobserveHolidays()
    {
        return $this->observeHoliday(false);
    }

    /**
     * Set all holidays as observed in the selected zone.
     *
     * @return \Closure
     */
    public function observeAllHolidays()
    {
        return $this->observeHoliday(true, static::OBSERVE_ALL_HOLIDAYS);
    }

    /**
     * Set all holidays as observed in the selected zone.
     *
     * @return \Closure
     */
    public function unobserveAllHolidays()
    {
        return $this->observeHoliday(false, static::OBSERVE_ALL_HOLIDAYS);
    }

    /**
     * Checks the date to see if it is a holiday.
     *
     * @return \Closure
     */
    public function isObservedHoliday()
    {
        $mixin = $this;
        $getThisOrToday = static::getThisOrToday();
        $allHolidays = static::OBSERVE_ALL_HOLIDAYS;

        return function ($name = null, $self = null) use ($mixin, $getThisOrToday, $allHolidays) {
            if ($name instanceof \DateTime || $name instanceof \DateTimeInterface) {
                $self = $name;
                $name = null;
            }

            $zone = $mixin->observedHolidaysZone;
            $days = isset($mixin->observedHolidays[$zone]) ? $mixin->observedHolidays[$zone] : array();

            if (!$name) {
                /** @var Carbon|BusinessDay $self */
                $self = $getThisOrToday($self, isset($this) ? $this : null);
                $name = $self->getHolidayId();
            }

            if ($name) {
                if (isset($days[$name])) {
                    return (bool) $days[$name];
                }
                if (isset($days[$allHolidays])) {
                    return (bool) $days[$allHolidays];
                }
            }

            return false;
        };
    }
}
