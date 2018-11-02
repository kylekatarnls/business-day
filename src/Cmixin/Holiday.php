<?php

namespace Cmixin;

use Carbon\Carbon;

class Holiday extends HolidaysList
{
    /**
     * Get the identifier of the current holiday or false if it's not an holiday.
     *
     * @return \Closure
     */
    public function getHolidayId()
    {
        $carbonClass = static::getCarbonClass();
        $getThisOrToday = static::getThisOrToday();

        return function ($self = null) use ($carbonClass, $getThisOrToday) {
            /** @var Carbon|BusinessDay $self */
            $self = $getThisOrToday($self, isset($this) ? $this : null);

            $holidays = $carbonClass::getHolidays();
            $date = $self->format('d/m');
            foreach ($holidays as $key => $holiday) {
                if (is_callable($holiday)) {
                    $holiday = call_user_func($holiday, $self->year);
                }

                if ($date === $holiday) {
                    return $key;
                }
            }

            return false;
        };
    }

    /**
     * Checks the date to see if it is an holiday.
     *
     * @return \Closure
     */
    public function isHoliday()
    {
        $getThisOrToday = static::getThisOrToday();

        return function ($self = null) use ($getThisOrToday) {
            /** @var Carbon|BusinessDay $self */
            $self = $getThisOrToday($self, isset($this) ? $this : null);

            return $self->getHolidayId() !== false;
        };
    }
}
