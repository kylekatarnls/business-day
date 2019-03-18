<?php

namespace Cmixin\BusinessDay;

class BusinessCalendar extends HolidayObserver
{
    /**
     * Checks the date to see if it is a business day.
     *
     * @return \Closure
     */
    public function isBusinessDay()
    {
        $mixin = $this;
        $getThisOrToday = static::getThisOrToday();

        return function ($self = null) use ($getThisOrToday, $mixin) {
            /** @var \Carbon\Carbon|\Cmixin\BusinessDay $self */
            $self = $getThisOrToday($self, isset($this) && $this !== $mixin ? $this : null);

            return $self->isWeekday() && !$self->isHoliday();
        };
    }

    /**
     * Sets the date to the next business day.
     *
     * @param string $method addDay() method by default
     *
     * @return \Closure
     */
    public function nextBusinessDay($method = 'addDay')
    {
        $mixin = $this;
        $getThisOrToday = static::getThisOrToday();

        return function ($self = null) use ($mixin, $getThisOrToday, $method) {
            /** @var static $self */
            $self = $getThisOrToday($self, isset($this) && $this !== $mixin ? $this : null);

            do {
                $self->$method();
            } while (!$self->isBusinessDay());

            return $self;
        };
    }

    /**
     * Sets the date to the current or next business day.
     *
     * @param string $method addDay() method by default
     *
     * @return \Closure
     */
    public function currentOrNextBusinessDay($method = 'nextBusinessDay')
    {
        $mixin = $this;
        $getThisOrToday = static::getThisOrToday();

        return function ($self = null) use ($mixin, $getThisOrToday, $method) {
            $self = $getThisOrToday($self, isset($this) && $this !== $mixin ? $this : null);

            return $self->isBusinessDay() ? $self : $self->$method();
        };
    }

    /**
     * Sets the date to the previous business day.
     *
     * @return \Closure
     */
    public function previousBusinessDay()
    {
        return $this->nextBusinessDay('subDay');
    }

    /**
     * Sets the date to the current or previous business day.
     *
     * @return \Closure
     */
    public function currentOrPreviousBusinessDay()
    {
        return $this->currentOrNextBusinessDay('previousBusinessDay');
    }
}
