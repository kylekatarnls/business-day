<?php

namespace Cmixin\BusinessDay;

use Carbon\Carbon;
use Cmixin\BusinessDay;
use Cmixin\BusinessDay\Util\Context;

trait HolidayData
{
    public $holidayData = [];

    /**
     * Get stored data set for the a given holiday ID.
     *
     * @return \Closure
     */
    public function getHolidayDataById()
    {
        $mixin = $this;

        /**
         * Get stored data set for the a given holiday ID.
         *
         * @return array|null
         */
        return static function (string $id) use ($mixin): ?array {
            return $mixin->holidayData[$id] ?? [];
        };
    }

    /**
     * Set stored data set for the a given holiday ID.
     *
     * @return \Closure
     */
    public function setHolidayDataById()
    {
        $mixin = $this;

        /**
         * Set stored data set for the a given holiday ID.
         *
         * @return $this|null
         */
        return static function (string $id, array $data) use ($mixin) {
            $mixin->holidayData[$id] = $data;

            return isset($this) && Context::isNotMixin($this, $mixin) ? $this : null;
        };
    }

    /**
     * Get stored data set for the current holiday or null if it's not a holiday.
     *
     * @return \Closure
     */
    public function getHolidayData()
    {
        $mixin = $this;

        /**
         * Get stored data set for the current holiday or null if it's not a holiday.
         *
         * @return array|null
         */
        return static function (): ?array {
            /** @var Carbon|BusinessDay $self */
            $self = static::this();
            $holidayId = $self->getHolidayId();

            if (!$holidayId) {
                return null;
            }

            return static::getHolidayDataById($holidayId);
        };
    }

    /**
     * Set stored data set for the current holiday, does nothing if it's not a holiday.
     *
     * @return \Closure
     */
    public function setHolidayData()
    {
        /**
         * Set stored data set for the current holiday, does nothing if it's not a holiday.
         *
         * @return $this|null
         */
        return static function (array $data) {
            /** @var Carbon|BusinessDay $self */
            $self = static::this();
            $holidayId = $self->getHolidayId();

            if (!$holidayId) {
                return null;
            }

            $carbonClass = get_class($self);

            return $carbonClass::setHolidayDataById($holidayId, $data);
        };
    }
}
