<?php

namespace Cmixin\BusinessDay;

use Cmixin\BusinessDay\Util\Context;
use InvalidArgumentException;

class HolidayObserver extends Holiday
{
    const OBSERVE_ALL_HOLIDAYS = 'all';

    /**
     * @var array
     */
    public $observedHolidays = [];

    /**
     * @var string
     */
    public $observedHolidaysZone = 'default';

    /**
     * Set the selected zone for observed holidays. So next observe methods will be saved and considered in this
     * given custom zone.
     *
     * @return \Closure
     */
    public function setObservedHolidaysZone()
    {
        $mixin = $this;

        /**
         * Set the selected zone for observed holidays. So next observe methods will be saved and considered in this
         * given custom zone.
         *
         * @param string $zone
         *
         * @return $this|null
         */
        return function ($zone, $self = null) use ($mixin) {
            $mixin->observedHolidaysZone = $zone;

            return isset($this) && Context::isNotMixin($this, $mixin) ? $this : (isset($self) ? $self : null);
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

        /**
         * Get the selected zone for observed holidays.
         *
         * @return string|null
         */
        return static function () use ($mixin) {
            return $mixin->observedHolidaysZone;
        };
    }

    /**
     * Set a holiday as observed/unobserved in the selected zone.
     *
     * @return \Closure
     */
    public function setHolidayObserveStatus()
    {
        $mixin = $this;
        $allHolidays = static::OBSERVE_ALL_HOLIDAYS;

        /**
         * Set a holiday as observed/unobserved in the selected zone.
         *
         * @param string $holidayId ID key of the holiday
         * @param bool   $observed  observed state
         *
         * @return $this|null
         */
        return function ($holidayId, $observed, $self = null) use ($mixin, $allHolidays) {
            if (!is_string($holidayId)) {
                throw new InvalidArgumentException(
                    'You must pass holiday names as a string or "'.$allHolidays.'".'
                );
            }

            $zone = $mixin->observedHolidaysZone;
            if ($holidayId === $allHolidays || !isset($mixin->observedHolidays[$zone])) {
                $mixin->observedHolidays[$zone] = [];
            }

            $mixin->observedHolidays[$zone][$holidayId] = $observed;

            return isset($this) && Context::isNotMixin($this, $mixin) ? $this : (isset($self) ? $self : null);
        };
    }

    /**
     * Set a holiday as observed/unobserved in the selected zone (can take array of holidays).
     *
     * @return \Closure
     */
    public function getObserveHolidayMethod($defaultValue = null, $defaultDay = null)
    {
        $mixin = $this;

        /**
         * Set a holiday as observed/unobserved in the selected zone (can take array of holidays).
         *
         * @param string|array $holidayId ID key of the holiday
         * @param bool         $observed  observed state
         *
         * @return $this|null
         */
        return function ($holidayId = null, $observed = null, $self = null) use ($mixin, $defaultValue, $defaultDay) {
            if (!$holidayId && $defaultDay) {
                $holidayId = $defaultDay;
            }

            if ($observed === null) {
                $observed = $defaultValue;
            }

            $days = (array) $holidayId;
            $setter = $mixin->setHolidayObserveStatus();

            foreach ($days as $holidayId) {
                $setter($holidayId, $observed);
            }

            return isset($this) && Context::isNotMixin($this, $mixin) ? $this : (isset($self) ? $self : null);
        };
    }

    /**
     * Set a holiday as observed in the selected zone.
     *
     * @return \Closure
     */
    public function observeHoliday()
    {
        /**
         * Set a holiday as observed in the selected zone.
         *
         * @param string $holidayId ID key of the holiday
         *
         * @return $this|null
         */
        return $this->getObserveHolidayMethod(true);
    }

    /**
     * Set a holiday as not observed in the selected zone.
     *
     * @return \Closure
     */
    public function unobserveHoliday()
    {
        /**
         * Set a holiday as not observed in the selected zone.
         *
         * @param string $holidayId ID key of the holiday
         *
         * @return $this|null
         */
        return $this->getObserveHolidayMethod(false);
    }

    /**
     * Set a holiday as observed in the selected zone.
     *
     * @return \Closure
     */
    public function observeHolidays()
    {
        /**
         * Set a holiday as observed in the selected zone.
         *
         * @param array $holidayIds ID keys of the holidays
         *
         * @return $this|null
         */
        return $this->getObserveHolidayMethod(true);
    }

    /**
     * Set a holiday as not observed in the selected zone.
     *
     * @return \Closure
     */
    public function unobserveHolidays()
    {
        /**
         * Set a holiday as not observed in the selected zone.
         *
         * @param array $holidayIds ID keys of the holidays
         *
         * @return $this|null
         */
        return $this->getObserveHolidayMethod(false);
    }

    /**
     * Set all holidays as observed in the selected zone.
     *
     * @return \Closure
     */
    public function observeAllHolidays()
    {
        /**
         * Set all holidays as observed in the selected zone.
         *
         * @return $this|null
         */
        return $this->getObserveHolidayMethod(true, static::OBSERVE_ALL_HOLIDAYS);
    }

    /**
     * Set all holidays as observed in the selected zone.
     *
     * @return \Closure
     */
    public function unobserveAllHolidays()
    {
        /**
         * Set all holidays as observed in the selected zone.
         *
         * @return $this|null
         */
        return $this->getObserveHolidayMethod(false, static::OBSERVE_ALL_HOLIDAYS);
    }

    /**
     * Check if a given holiday ID is observed in the selected zone.
     *
     * @return \Closure
     */
    public function checkObservedHoliday()
    {
        $mixin = $this;
        $allHolidays = static::OBSERVE_ALL_HOLIDAYS;

        /**
         * Check if a given holiday ID is observed in the selected zone.
         *
         * @param string|false|null $holidayId
         *
         * @return bool
         */
        return static function ($holidayId = null) use ($mixin, $allHolidays) {
            $zone = $mixin->observedHolidaysZone;
            $days = isset($mixin->observedHolidays[$zone]) ? $mixin->observedHolidays[$zone] : [];

            if ($holidayId) {
                if (isset($days[$holidayId])) {
                    return (bool) $days[$holidayId];
                }

                if (isset($days[$allHolidays])) {
                    return (bool) $days[$allHolidays];
                }
            }

            return false;
        };
    }

    /**
     * Checks the date to see if it is a holiday observed in the selected zone.
     *
     * @return \Closure
     */
    public function isObservedHoliday()
    {
        /**
         * Checks the date to see if it is a holiday observed in the selected zone.
         *
         * @param string $holidayId holiday ID to check (current date used instead if omitted)
         *
         * @return bool
         */
        return function ($holidayId = null, $date = null) {
            $self = static::this();

            [$holidayId, $date] = static::swapDateTimeParam($holidayId, $date, null);
            $holidayId = $holidayId ?? (is_object($holidayId) ? null : $holidayId);
            $date = is_object($date) ? $self->resolveCarbon($date) : $self;

            if (!$holidayId) {
                $holidayId = $date->getHolidayId();
            }

            $carbonClass = get_class($date);

            return $carbonClass::checkObservedHoliday($holidayId);
        };
    }
}
