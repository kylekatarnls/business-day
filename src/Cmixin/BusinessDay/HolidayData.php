<?php

namespace Cmixin\BusinessDay;

use Carbon\Carbon;
use Cmixin\BusinessDay;
use Exception;

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
        return function (string $id) use ($mixin): ?array {
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
        return function (string $id, array $data) use ($mixin) {
            $mixin->holidayData[$id] = $data;

            return isset($this) && $this !== $mixin ? $this : null;
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
        return function ($self = null) use ($mixin): ?array {
            $carbonClass = @get_class() ?: Emulator::getClass(new Exception());

            /** @var Carbon|BusinessDay $self */
            $self = $carbonClass::getThisOrToday($self, isset($this) && $this !== $mixin ? $this : null);
            $holidayId = $self->getHolidayId();

            if (!$holidayId) {
                return null;
            }

            return $carbonClass::getHolidayDataById($holidayId);
        };
    }

    /**
     * Set stored data set for the current holiday, does nothing if it's not a holiday.
     *
     * @return \Closure
     */
    public function setHolidayData()
    {
        $mixin = $this;

        /**
         * Set stored data set for the current holiday, does nothing if it's not a holiday.
         *
         * @return $this|null
         */
        return function (array $data, $self = null) use ($mixin) {
            $carbonClass = @get_class() ?: Emulator::getClass(new Exception());

            /** @var Carbon|BusinessDay $self */
            $self = $carbonClass::getThisOrToday($self, isset($this) && $this !== $mixin ? $this : null);
            $holidayId = $self->getHolidayId();

            if (!$holidayId) {
                return null;
            }

            return $carbonClass::setHolidayDataById($holidayId, $data);
        };
    }
}
