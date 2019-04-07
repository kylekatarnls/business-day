<?php

namespace Carbon
{
    class Carbon
    {
        /**
         * @see \Cmixin\BusinessDay::addBusinessDays
         *
         * Add a given number of business days to the current date.
         *
         * @param int $days
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        public function addBusinessDays($days = 1, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:28
        }

        /**
         * @see \Cmixin\BusinessDay::addBusinessDay
         *
         * Add one business day to the current date.
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        public function addBusinessDay($days = 1, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:28
        }

        /**
         * @see \Cmixin\BusinessDay::subBusinessDays
         *
         * Subtract a given number of business days to the current date.
         *
         * @param int $days
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        public function subBusinessDays($days = 1, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:28
        }

        /**
         * @see \Cmixin\BusinessDay::subtractBusinessDays
         *
         * Subtract a given number of business days to the current date.
         *
         * @param int $days
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        public function subtractBusinessDays($days = 1, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:28
        }

        /**
         * @see \Cmixin\BusinessDay::subBusinessDay
         *
         * Subtract one business day to the current date.
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        public function subBusinessDay($days = 1, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:28
        }

        /**
         * @see \Cmixin\BusinessDay::subtractBusinessDay
         *
         * Subtract one business day to the current date.
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        public function subtractBusinessDay($days = 1, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:28
        }

        /**
         * @see \Cmixin\BusinessDay::diffInBusinessDays
         *
         * Returns the difference between 2 dates in business days.
         *
         * @param \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface $other other date
         *
         * @return int
         */
        public function diffInBusinessDays($other = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:149
        }

        /**
         * @see \Cmixin\BusinessDay::getBusinessDaysInMonth
         *
         * Get the number of business days in the current month.
         *
         * @return int
         */
        public function getBusinessDaysInMonth($self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:177
        }

        /**
         * @see \Cmixin\BusinessDay::getMonthBusinessDays
         *
         * Get list of date objects for each business day in the current month.
         *
         * @return array
         */
        public function getMonthBusinessDays($self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:202
        }

        /**
         * @see \Cmixin\BusinessDay\BusinessCalendar::isBusinessDay
         *
         * Checks the date to see if it is a business day (neither a weekend day nor a holiday).
         *
         * @return bool
         */
        public function isBusinessDay($self = null)
        {
            // Content, see src/Cmixin/BusinessDay/BusinessCalendar.php:21
        }

        /**
         * @see \Cmixin\BusinessDay\BusinessCalendar::nextBusinessDay
         *
         * Sets the date to the next business day (neither a weekend day nor a holiday).
         *
         * @return bool
         */
        public function nextBusinessDay($self = null)
        {
            // Content, see src/Cmixin/BusinessDay/BusinessCalendar.php:47
        }

        /**
         * @see \Cmixin\BusinessDay\BusinessCalendar::currentOrNextBusinessDay
         *
         * Sets the date to the current or next business day (neither a weekend day nor a holiday).
         *
         * @return bool
         */
        public function currentOrNextBusinessDay($self = null)
        {
            // Content, see src/Cmixin/BusinessDay/BusinessCalendar.php:77
        }

        /**
         * @see \Cmixin\BusinessDay\BusinessCalendar::previousBusinessDay
         *
         * Sets the date to the previous business day (neither a weekend day nor a holiday).
         *
         * @return bool
         */
        public function previousBusinessDay($self = null)
        {
            // Content, see src/Cmixin/BusinessDay/BusinessCalendar.php:47
        }

        /**
         * @see \Cmixin\BusinessDay\BusinessCalendar::currentOrPreviousBusinessDay
         *
         * Sets the date to the current or previous business day.
         *
         * @return bool
         */
        public function currentOrPreviousBusinessDay($self = null)
        {
            // Content, see src/Cmixin/BusinessDay/BusinessCalendar.php:77
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::setObservedHolidaysZone
         *
         * Set the selected zone for observed holidays. So next observe methods will be saved and considered in this
         * given custom zone.
         *
         * @param string $zone
         *
         * @return $this|null
         */
        public function setObservedHolidaysZone($zone, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:40
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::getObservedHolidaysZone
         *
         * Get the selected zone for observed holidays.
         *
         * @return string|null
         */
        public function getObservedHolidaysZone()
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:61
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::setHolidayObserveStatus
         *
         * Set a holiday as observed/unobserved in the selected zone.
         *
         * @param string $holidayId ID key of the holiday
         * @param bool   $observed  observed state
         *
         * @return $this|null
         */
        public function setHolidayObserveStatus($holidayId, $observed, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:84
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::getObserveHolidayMethod
         *
         * Set a holiday as observed/unobserved in the selected zone (can take array of holidays).
         *
         * @param string|array $holidayId ID key of the holiday
         * @param bool         $observed  observed state
         *
         * @return $this|null
         */
        public function getObserveHolidayMethod($holidayId = null, $observed = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:119
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::observeHoliday
         *
         * Set a holiday as observed in the selected zone.
         *
         * @param string $holidayId ID key of the holiday
         *
         * @return $this|null
         */
        public function observeHoliday($holidayId = null, $observed = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:119
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::unobserveHoliday
         *
         * Set a holiday as not observed in the selected zone.
         *
         * @param string $holidayId ID key of the holiday
         *
         * @return $this|null
         */
        public function unobserveHoliday($holidayId = null, $observed = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:119
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::observeHolidays
         *
         * Set a holiday as observed in the selected zone.
         *
         * @param array $holidayIds ID keys of the holidays
         *
         * @return $this|null
         */
        public function observeHolidays($holidayId = null, $observed = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:119
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::unobserveHolidays
         *
         * Set a holiday as not observed in the selected zone.
         *
         * @param array $holidayIds ID keys of the holidays
         *
         * @return $this|null
         */
        public function unobserveHolidays($holidayId = null, $observed = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:119
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::observeAllHolidays
         *
         * Set all holidays as observed in the selected zone.
         *
         * @return $this|null
         */
        public function observeAllHolidays($holidayId = null, $observed = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:119
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::unobserveAllHolidays
         *
         * Set all holidays as observed in the selected zone.
         *
         * @return $this|null
         */
        public function unobserveAllHolidays($holidayId = null, $observed = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:119
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::checkObservedHoliday
         *
         * Check if a given holiday ID is observed in the selected zone.
         *
         * @param string|false|null $holidayId
         *
         * @return bool
         */
        public function checkObservedHoliday($holidayId = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:254
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::isObservedHoliday
         *
         * Checks the date to see if it is a holiday observed in the selected zone.
         *
         * @param string $holidayId holiday ID to check (current date used instead if omitted)
         *
         * @return bool
         */
        public function isObservedHoliday($holidayId = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:288
        }

        /**
         * @see \Cmixin\BusinessDay\Holiday::getHolidayId
         *
         * Get the identifier of the current holiday or false if it's not a holiday.
         *
         * @return string|false
         */
        public function getHolidayId($self = null)
        {
            // Content, see src/Cmixin/BusinessDay/Holiday.php:28
        }

        /**
         * @see \Cmixin\BusinessDay\Holiday::isHoliday
         *
         * Checks the date to see if it is a holiday.
         *
         * @return bool
         */
        public function isHoliday($self = null)
        {
            // Content, see src/Cmixin/BusinessDay/Holiday.php:65
        }

        /**
         * @see \Cmixin\BusinessDay\Holiday::getHolidayNamesDictionary
         *
         * Get the holidays in the given language.
         *
         * @param string $locale language
         *
         * @return array
         */
        public function getHolidayNamesDictionary($locale)
        {
            // Content, see src/Cmixin/BusinessDay/Holiday.php:92
        }

        /**
         * @see \Cmixin\BusinessDay\Holiday::getHolidayName
         *
         * Get the name of the current holiday (using the locale given in parameter or the current date locale)
         * or false if it's not a holiday.
         *
         * @param string $locale language ("en" by default)
         *
         * @return string|false
         */
        public function getHolidayName($locale = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/Holiday.php:132
        }

        /**
         * @see \Cmixin\BusinessDay\YearCrawler::getYearHolidays
         *
         * Get the holidays dates for a given year (current year if no parameter given).
         *
         * @param int    $year input year, year of the current instance or context used if omitted, current year used if omitted and called statically
         * @param string $type can be 'string' (to return dates as string) or a class name to returns instances of this class
         *
         * @return array
         */
        public function getYearHolidays($year = null, $type = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/YearCrawler.php:22
        }

        /**
         * @see \Cmixin\BusinessDay\YearCrawler::getYearHolidaysNextFunction
         *
         * Get a next() callback to call to iterate over holidays of a year.
         *
         * @param int    $year input year, year of the current instance or context used if omitted, current year used if omitted and called statically
         * @param string $type can be 'string' (to return dates as string) or a class name to returns instances of this class
         *
         * @return \Closure
         */
        public function getYearHolidaysNextFunction($year = null, $type = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/YearCrawler.php:54
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::standardizeHolidaysRegion
         *
         * Return a standardized region name.
         *
         * @param string $region
         *
         * @return string
         */
        public function standardizeHolidaysRegion($region)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:31
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::setHolidaysRegion
         *
         * Set the holidays region (see src/Cmixin/Holidays for examples).
         *
         * @param string $region
         */
        public function setHolidaysRegion($region)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:52
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::getHolidaysRegion
         *
         * Get the current holidays region.
         *
         * @return null|string
         */
        public function getHolidaysRegion()
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:101
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::getHolidays
         *
         * Get the holidays for the current region selected.
         *
         * @param string $region
         *
         * @return array
         */
        public function getHolidays($region = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:122
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::setHolidays
         *
         * Set the holidays list.
         *
         * @param string $region
         * @param array  $holidays
         */
        public function setHolidays($region, $holidays)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:150
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::resetHolidays
         *
         * Reset the holidays list.
         */
        public function resetHolidays()
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:170
        }

        public function initializeHolidaysRegion()
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:195
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::pushHoliday
         *
         * Push a holiday to the holidays list of a region.
         *
         * @param string          $region    region where the holiday is observed.
         * @param string|\Closure $holiday   date or closure that get the year as parameter and returns the date
         * @param string          $holidayId optional holiday ID
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface|null
         */
        public function pushHoliday($region, $holiday, $holidayId = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:218
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::setHolidayName
         *
         * Set/change the name of holiday by ID for a given language (or a list of languages).
         *
         * @param string       $holidayKey holiday ID (identifier key)
         * @param string|array $language   language 2-chars code (or an array with languages codes as keys and new names for each language as value).
         * @param string       $name       new name (ignored if $language is an array)
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface|null
         */
        public function setHolidayName($holidayKey = null, $language = null, $name = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:252
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::addHoliday
         *
         * Add a holiday to the holidays list of a region and optionally init its ID, name and observed state.
         *
         * @param string          $region    region where the holiday is observed.
         * @param string|\Closure $holiday   date or closure that get the year as parameter and returns the date
         * @param string          $holidayId optional holiday ID
         * @param string          $name      optional name
         * @param bool            $observed  optional observed state
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface|null
         */
        public function addHoliday($region, $holiday, $holidayId = null, $name = null, $observed = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:291
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::unpackHoliday
         *
         * Unpack a holiday array definition.
         *
         * @param array  &$holiday
         * @param string &$name
         * @param bool   &$observed
         *
         * @return array
         */
        public function unpackHoliday($holiday, $name = null, $observed = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:329
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::checkHoliday
         *
         * Check a holiday definition and unpack it if it's an array.
         *
         * @param array  &$holiday
         * @param string $holidayId
         * @param string &$name
         * @param bool   &$observed
         *
         * @return array
         */
        public function checkHoliday($holiday, $holidayId, $name = null, $observed = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:369
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::addHolidays
         *
         * Add a holiday to the holidays list of a region and optionally init their IDs, names and observed states (if provided as array-definitions).
         *
         * @param string $region
         * @param array  $holidays
         */
        public function addHolidays($region, $holidays)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:401
        }

        public function enable()
        {
            // Content, see src/Cmixin/BusinessDay/MixinBase.php:29
        }

        /**
         * @see \Cmixin\BusinessDay\MixinBase::getThisOrToday
         *
         * Return current context $this or Carbon::today() if called statically.
         *
         * @param \Carbon\CarbonInterface $self
         * @param \Carbon\CarbonInterface $context
         *
         * @return \Carbon\CarbonInterface|\Carbon\Carbon|\Carbon\CarbonImmutable
         */
        public function getThisOrToday($self, $context)
        {
            // Content, see src/Cmixin/BusinessDay/MixinBase.php:71
        }

        /**
         * @see \Cmixin\BusinessDay\MixinBase::isDateTimeInstance
         *
         * Return true if the given value is a DateTime or DateTimeInterface.
         *
         * @param mixed $value
         *
         * @return bool
         */
        public function isDateTimeInstance($value)
        {
            // Content, see src/Cmixin/BusinessDay/MixinBase.php:96
        }

        /**
         * @see \Cmixin\BusinessDay\MixinBase::swapDateTimeParam
         *
         * Store a first variable as Carbon instance into the second variable if the first one is a date.
         *
         * @param mixed $date         First variable to check if it's a date (DateTime or DateTimeInterface)
         * @param mixed $target       Target variable that will be replaced by the first one if it's a date
         * @param mixed $defaultValue Value to store in the first variable if it's a date
         *
         * @return array the new pair of variables
         */
        public function swapDateTimeParam($date, $target, $defaultValue)
        {
            // Content, see src/Cmixin/BusinessDay/MixinBase.php:117
        }
    }
}

namespace Carbon
{
    class CarbonImmutable
    {
        /**
         * @see \Cmixin\BusinessDay::addBusinessDays
         *
         * Add a given number of business days to the current date.
         *
         * @param int $days
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        public function addBusinessDays($days = 1, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:28
        }

        /**
         * @see \Cmixin\BusinessDay::addBusinessDay
         *
         * Add one business day to the current date.
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        public function addBusinessDay($days = 1, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:28
        }

        /**
         * @see \Cmixin\BusinessDay::subBusinessDays
         *
         * Subtract a given number of business days to the current date.
         *
         * @param int $days
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        public function subBusinessDays($days = 1, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:28
        }

        /**
         * @see \Cmixin\BusinessDay::subtractBusinessDays
         *
         * Subtract a given number of business days to the current date.
         *
         * @param int $days
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        public function subtractBusinessDays($days = 1, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:28
        }

        /**
         * @see \Cmixin\BusinessDay::subBusinessDay
         *
         * Subtract one business day to the current date.
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        public function subBusinessDay($days = 1, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:28
        }

        /**
         * @see \Cmixin\BusinessDay::subtractBusinessDay
         *
         * Subtract one business day to the current date.
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        public function subtractBusinessDay($days = 1, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:28
        }

        /**
         * @see \Cmixin\BusinessDay::diffInBusinessDays
         *
         * Returns the difference between 2 dates in business days.
         *
         * @param \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface $other other date
         *
         * @return int
         */
        public function diffInBusinessDays($other = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:149
        }

        /**
         * @see \Cmixin\BusinessDay::getBusinessDaysInMonth
         *
         * Get the number of business days in the current month.
         *
         * @return int
         */
        public function getBusinessDaysInMonth($self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:177
        }

        /**
         * @see \Cmixin\BusinessDay::getMonthBusinessDays
         *
         * Get list of date objects for each business day in the current month.
         *
         * @return array
         */
        public function getMonthBusinessDays($self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:202
        }

        /**
         * @see \Cmixin\BusinessDay\BusinessCalendar::isBusinessDay
         *
         * Checks the date to see if it is a business day (neither a weekend day nor a holiday).
         *
         * @return bool
         */
        public function isBusinessDay($self = null)
        {
            // Content, see src/Cmixin/BusinessDay/BusinessCalendar.php:21
        }

        /**
         * @see \Cmixin\BusinessDay\BusinessCalendar::nextBusinessDay
         *
         * Sets the date to the next business day (neither a weekend day nor a holiday).
         *
         * @return bool
         */
        public function nextBusinessDay($self = null)
        {
            // Content, see src/Cmixin/BusinessDay/BusinessCalendar.php:47
        }

        /**
         * @see \Cmixin\BusinessDay\BusinessCalendar::currentOrNextBusinessDay
         *
         * Sets the date to the current or next business day (neither a weekend day nor a holiday).
         *
         * @return bool
         */
        public function currentOrNextBusinessDay($self = null)
        {
            // Content, see src/Cmixin/BusinessDay/BusinessCalendar.php:77
        }

        /**
         * @see \Cmixin\BusinessDay\BusinessCalendar::previousBusinessDay
         *
         * Sets the date to the previous business day (neither a weekend day nor a holiday).
         *
         * @return bool
         */
        public function previousBusinessDay($self = null)
        {
            // Content, see src/Cmixin/BusinessDay/BusinessCalendar.php:47
        }

        /**
         * @see \Cmixin\BusinessDay\BusinessCalendar::currentOrPreviousBusinessDay
         *
         * Sets the date to the current or previous business day.
         *
         * @return bool
         */
        public function currentOrPreviousBusinessDay($self = null)
        {
            // Content, see src/Cmixin/BusinessDay/BusinessCalendar.php:77
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::setObservedHolidaysZone
         *
         * Set the selected zone for observed holidays. So next observe methods will be saved and considered in this
         * given custom zone.
         *
         * @param string $zone
         *
         * @return $this|null
         */
        public function setObservedHolidaysZone($zone, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:40
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::getObservedHolidaysZone
         *
         * Get the selected zone for observed holidays.
         *
         * @return string|null
         */
        public function getObservedHolidaysZone()
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:61
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::setHolidayObserveStatus
         *
         * Set a holiday as observed/unobserved in the selected zone.
         *
         * @param string $holidayId ID key of the holiday
         * @param bool   $observed  observed state
         *
         * @return $this|null
         */
        public function setHolidayObserveStatus($holidayId, $observed, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:84
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::getObserveHolidayMethod
         *
         * Set a holiday as observed/unobserved in the selected zone (can take array of holidays).
         *
         * @param string|array $holidayId ID key of the holiday
         * @param bool         $observed  observed state
         *
         * @return $this|null
         */
        public function getObserveHolidayMethod($holidayId = null, $observed = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:119
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::observeHoliday
         *
         * Set a holiday as observed in the selected zone.
         *
         * @param string $holidayId ID key of the holiday
         *
         * @return $this|null
         */
        public function observeHoliday($holidayId = null, $observed = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:119
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::unobserveHoliday
         *
         * Set a holiday as not observed in the selected zone.
         *
         * @param string $holidayId ID key of the holiday
         *
         * @return $this|null
         */
        public function unobserveHoliday($holidayId = null, $observed = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:119
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::observeHolidays
         *
         * Set a holiday as observed in the selected zone.
         *
         * @param array $holidayIds ID keys of the holidays
         *
         * @return $this|null
         */
        public function observeHolidays($holidayId = null, $observed = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:119
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::unobserveHolidays
         *
         * Set a holiday as not observed in the selected zone.
         *
         * @param array $holidayIds ID keys of the holidays
         *
         * @return $this|null
         */
        public function unobserveHolidays($holidayId = null, $observed = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:119
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::observeAllHolidays
         *
         * Set all holidays as observed in the selected zone.
         *
         * @return $this|null
         */
        public function observeAllHolidays($holidayId = null, $observed = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:119
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::unobserveAllHolidays
         *
         * Set all holidays as observed in the selected zone.
         *
         * @return $this|null
         */
        public function unobserveAllHolidays($holidayId = null, $observed = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:119
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::checkObservedHoliday
         *
         * Check if a given holiday ID is observed in the selected zone.
         *
         * @param string|false|null $holidayId
         *
         * @return bool
         */
        public function checkObservedHoliday($holidayId = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:254
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::isObservedHoliday
         *
         * Checks the date to see if it is a holiday observed in the selected zone.
         *
         * @param string $holidayId holiday ID to check (current date used instead if omitted)
         *
         * @return bool
         */
        public function isObservedHoliday($holidayId = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:288
        }

        /**
         * @see \Cmixin\BusinessDay\Holiday::getHolidayId
         *
         * Get the identifier of the current holiday or false if it's not a holiday.
         *
         * @return string|false
         */
        public function getHolidayId($self = null)
        {
            // Content, see src/Cmixin/BusinessDay/Holiday.php:28
        }

        /**
         * @see \Cmixin\BusinessDay\Holiday::isHoliday
         *
         * Checks the date to see if it is a holiday.
         *
         * @return bool
         */
        public function isHoliday($self = null)
        {
            // Content, see src/Cmixin/BusinessDay/Holiday.php:65
        }

        /**
         * @see \Cmixin\BusinessDay\Holiday::getHolidayNamesDictionary
         *
         * Get the holidays in the given language.
         *
         * @param string $locale language
         *
         * @return array
         */
        public function getHolidayNamesDictionary($locale)
        {
            // Content, see src/Cmixin/BusinessDay/Holiday.php:92
        }

        /**
         * @see \Cmixin\BusinessDay\Holiday::getHolidayName
         *
         * Get the name of the current holiday (using the locale given in parameter or the current date locale)
         * or false if it's not a holiday.
         *
         * @param string $locale language ("en" by default)
         *
         * @return string|false
         */
        public function getHolidayName($locale = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/Holiday.php:132
        }

        /**
         * @see \Cmixin\BusinessDay\YearCrawler::getYearHolidays
         *
         * Get the holidays dates for a given year (current year if no parameter given).
         *
         * @param int    $year input year, year of the current instance or context used if omitted, current year used if omitted and called statically
         * @param string $type can be 'string' (to return dates as string) or a class name to returns instances of this class
         *
         * @return array
         */
        public function getYearHolidays($year = null, $type = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/YearCrawler.php:22
        }

        /**
         * @see \Cmixin\BusinessDay\YearCrawler::getYearHolidaysNextFunction
         *
         * Get a next() callback to call to iterate over holidays of a year.
         *
         * @param int    $year input year, year of the current instance or context used if omitted, current year used if omitted and called statically
         * @param string $type can be 'string' (to return dates as string) or a class name to returns instances of this class
         *
         * @return \Closure
         */
        public function getYearHolidaysNextFunction($year = null, $type = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/YearCrawler.php:54
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::standardizeHolidaysRegion
         *
         * Return a standardized region name.
         *
         * @param string $region
         *
         * @return string
         */
        public function standardizeHolidaysRegion($region)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:31
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::setHolidaysRegion
         *
         * Set the holidays region (see src/Cmixin/Holidays for examples).
         *
         * @param string $region
         */
        public function setHolidaysRegion($region)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:52
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::getHolidaysRegion
         *
         * Get the current holidays region.
         *
         * @return null|string
         */
        public function getHolidaysRegion()
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:101
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::getHolidays
         *
         * Get the holidays for the current region selected.
         *
         * @param string $region
         *
         * @return array
         */
        public function getHolidays($region = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:122
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::setHolidays
         *
         * Set the holidays list.
         *
         * @param string $region
         * @param array  $holidays
         */
        public function setHolidays($region, $holidays)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:150
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::resetHolidays
         *
         * Reset the holidays list.
         */
        public function resetHolidays()
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:170
        }

        public function initializeHolidaysRegion()
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:195
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::pushHoliday
         *
         * Push a holiday to the holidays list of a region.
         *
         * @param string          $region    region where the holiday is observed.
         * @param string|\Closure $holiday   date or closure that get the year as parameter and returns the date
         * @param string          $holidayId optional holiday ID
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface|null
         */
        public function pushHoliday($region, $holiday, $holidayId = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:218
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::setHolidayName
         *
         * Set/change the name of holiday by ID for a given language (or a list of languages).
         *
         * @param string       $holidayKey holiday ID (identifier key)
         * @param string|array $language   language 2-chars code (or an array with languages codes as keys and new names for each language as value).
         * @param string       $name       new name (ignored if $language is an array)
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface|null
         */
        public function setHolidayName($holidayKey = null, $language = null, $name = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:252
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::addHoliday
         *
         * Add a holiday to the holidays list of a region and optionally init its ID, name and observed state.
         *
         * @param string          $region    region where the holiday is observed.
         * @param string|\Closure $holiday   date or closure that get the year as parameter and returns the date
         * @param string          $holidayId optional holiday ID
         * @param string          $name      optional name
         * @param bool            $observed  optional observed state
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface|null
         */
        public function addHoliday($region, $holiday, $holidayId = null, $name = null, $observed = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:291
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::unpackHoliday
         *
         * Unpack a holiday array definition.
         *
         * @param array  &$holiday
         * @param string &$name
         * @param bool   &$observed
         *
         * @return array
         */
        public function unpackHoliday($holiday, $name = null, $observed = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:329
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::checkHoliday
         *
         * Check a holiday definition and unpack it if it's an array.
         *
         * @param array  &$holiday
         * @param string $holidayId
         * @param string &$name
         * @param bool   &$observed
         *
         * @return array
         */
        public function checkHoliday($holiday, $holidayId, $name = null, $observed = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:369
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::addHolidays
         *
         * Add a holiday to the holidays list of a region and optionally init their IDs, names and observed states (if provided as array-definitions).
         *
         * @param string $region
         * @param array  $holidays
         */
        public function addHolidays($region, $holidays)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:401
        }

        public function enable()
        {
            // Content, see src/Cmixin/BusinessDay/MixinBase.php:29
        }

        /**
         * @see \Cmixin\BusinessDay\MixinBase::getThisOrToday
         *
         * Return current context $this or Carbon::today() if called statically.
         *
         * @param \Carbon\CarbonInterface $self
         * @param \Carbon\CarbonInterface $context
         *
         * @return \Carbon\CarbonInterface|\Carbon\Carbon|\Carbon\CarbonImmutable
         */
        public function getThisOrToday($self, $context)
        {
            // Content, see src/Cmixin/BusinessDay/MixinBase.php:71
        }

        /**
         * @see \Cmixin\BusinessDay\MixinBase::isDateTimeInstance
         *
         * Return true if the given value is a DateTime or DateTimeInterface.
         *
         * @param mixed $value
         *
         * @return bool
         */
        public function isDateTimeInstance($value)
        {
            // Content, see src/Cmixin/BusinessDay/MixinBase.php:96
        }

        /**
         * @see \Cmixin\BusinessDay\MixinBase::swapDateTimeParam
         *
         * Store a first variable as Carbon instance into the second variable if the first one is a date.
         *
         * @param mixed $date         First variable to check if it's a date (DateTime or DateTimeInterface)
         * @param mixed $target       Target variable that will be replaced by the first one if it's a date
         * @param mixed $defaultValue Value to store in the first variable if it's a date
         *
         * @return array the new pair of variables
         */
        public function swapDateTimeParam($date, $target, $defaultValue)
        {
            // Content, see src/Cmixin/BusinessDay/MixinBase.php:117
        }
    }
}

namespace Illuminate\Support
{
    class Carbon
    {
        /**
         * @see \Cmixin\BusinessDay::addBusinessDays
         *
         * Add a given number of business days to the current date.
         *
         * @param int $days
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        public function addBusinessDays($days = 1, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:28
        }

        /**
         * @see \Cmixin\BusinessDay::addBusinessDay
         *
         * Add one business day to the current date.
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        public function addBusinessDay($days = 1, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:28
        }

        /**
         * @see \Cmixin\BusinessDay::subBusinessDays
         *
         * Subtract a given number of business days to the current date.
         *
         * @param int $days
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        public function subBusinessDays($days = 1, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:28
        }

        /**
         * @see \Cmixin\BusinessDay::subtractBusinessDays
         *
         * Subtract a given number of business days to the current date.
         *
         * @param int $days
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        public function subtractBusinessDays($days = 1, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:28
        }

        /**
         * @see \Cmixin\BusinessDay::subBusinessDay
         *
         * Subtract one business day to the current date.
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        public function subBusinessDay($days = 1, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:28
        }

        /**
         * @see \Cmixin\BusinessDay::subtractBusinessDay
         *
         * Subtract one business day to the current date.
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        public function subtractBusinessDay($days = 1, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:28
        }

        /**
         * @see \Cmixin\BusinessDay::diffInBusinessDays
         *
         * Returns the difference between 2 dates in business days.
         *
         * @param \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface $other other date
         *
         * @return int
         */
        public function diffInBusinessDays($other = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:149
        }

        /**
         * @see \Cmixin\BusinessDay::getBusinessDaysInMonth
         *
         * Get the number of business days in the current month.
         *
         * @return int
         */
        public function getBusinessDaysInMonth($self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:177
        }

        /**
         * @see \Cmixin\BusinessDay::getMonthBusinessDays
         *
         * Get list of date objects for each business day in the current month.
         *
         * @return array
         */
        public function getMonthBusinessDays($self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:202
        }

        /**
         * @see \Cmixin\BusinessDay\BusinessCalendar::isBusinessDay
         *
         * Checks the date to see if it is a business day (neither a weekend day nor a holiday).
         *
         * @return bool
         */
        public function isBusinessDay($self = null)
        {
            // Content, see src/Cmixin/BusinessDay/BusinessCalendar.php:21
        }

        /**
         * @see \Cmixin\BusinessDay\BusinessCalendar::nextBusinessDay
         *
         * Sets the date to the next business day (neither a weekend day nor a holiday).
         *
         * @return bool
         */
        public function nextBusinessDay($self = null)
        {
            // Content, see src/Cmixin/BusinessDay/BusinessCalendar.php:47
        }

        /**
         * @see \Cmixin\BusinessDay\BusinessCalendar::currentOrNextBusinessDay
         *
         * Sets the date to the current or next business day (neither a weekend day nor a holiday).
         *
         * @return bool
         */
        public function currentOrNextBusinessDay($self = null)
        {
            // Content, see src/Cmixin/BusinessDay/BusinessCalendar.php:77
        }

        /**
         * @see \Cmixin\BusinessDay\BusinessCalendar::previousBusinessDay
         *
         * Sets the date to the previous business day (neither a weekend day nor a holiday).
         *
         * @return bool
         */
        public function previousBusinessDay($self = null)
        {
            // Content, see src/Cmixin/BusinessDay/BusinessCalendar.php:47
        }

        /**
         * @see \Cmixin\BusinessDay\BusinessCalendar::currentOrPreviousBusinessDay
         *
         * Sets the date to the current or previous business day.
         *
         * @return bool
         */
        public function currentOrPreviousBusinessDay($self = null)
        {
            // Content, see src/Cmixin/BusinessDay/BusinessCalendar.php:77
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::setObservedHolidaysZone
         *
         * Set the selected zone for observed holidays. So next observe methods will be saved and considered in this
         * given custom zone.
         *
         * @param string $zone
         *
         * @return $this|null
         */
        public function setObservedHolidaysZone($zone, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:40
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::getObservedHolidaysZone
         *
         * Get the selected zone for observed holidays.
         *
         * @return string|null
         */
        public function getObservedHolidaysZone()
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:61
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::setHolidayObserveStatus
         *
         * Set a holiday as observed/unobserved in the selected zone.
         *
         * @param string $holidayId ID key of the holiday
         * @param bool   $observed  observed state
         *
         * @return $this|null
         */
        public function setHolidayObserveStatus($holidayId, $observed, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:84
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::getObserveHolidayMethod
         *
         * Set a holiday as observed/unobserved in the selected zone (can take array of holidays).
         *
         * @param string|array $holidayId ID key of the holiday
         * @param bool         $observed  observed state
         *
         * @return $this|null
         */
        public function getObserveHolidayMethod($holidayId = null, $observed = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:119
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::observeHoliday
         *
         * Set a holiday as observed in the selected zone.
         *
         * @param string $holidayId ID key of the holiday
         *
         * @return $this|null
         */
        public function observeHoliday($holidayId = null, $observed = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:119
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::unobserveHoliday
         *
         * Set a holiday as not observed in the selected zone.
         *
         * @param string $holidayId ID key of the holiday
         *
         * @return $this|null
         */
        public function unobserveHoliday($holidayId = null, $observed = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:119
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::observeHolidays
         *
         * Set a holiday as observed in the selected zone.
         *
         * @param array $holidayIds ID keys of the holidays
         *
         * @return $this|null
         */
        public function observeHolidays($holidayId = null, $observed = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:119
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::unobserveHolidays
         *
         * Set a holiday as not observed in the selected zone.
         *
         * @param array $holidayIds ID keys of the holidays
         *
         * @return $this|null
         */
        public function unobserveHolidays($holidayId = null, $observed = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:119
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::observeAllHolidays
         *
         * Set all holidays as observed in the selected zone.
         *
         * @return $this|null
         */
        public function observeAllHolidays($holidayId = null, $observed = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:119
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::unobserveAllHolidays
         *
         * Set all holidays as observed in the selected zone.
         *
         * @return $this|null
         */
        public function unobserveAllHolidays($holidayId = null, $observed = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:119
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::checkObservedHoliday
         *
         * Check if a given holiday ID is observed in the selected zone.
         *
         * @param string|false|null $holidayId
         *
         * @return bool
         */
        public function checkObservedHoliday($holidayId = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:254
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::isObservedHoliday
         *
         * Checks the date to see if it is a holiday observed in the selected zone.
         *
         * @param string $holidayId holiday ID to check (current date used instead if omitted)
         *
         * @return bool
         */
        public function isObservedHoliday($holidayId = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:288
        }

        /**
         * @see \Cmixin\BusinessDay\Holiday::getHolidayId
         *
         * Get the identifier of the current holiday or false if it's not a holiday.
         *
         * @return string|false
         */
        public function getHolidayId($self = null)
        {
            // Content, see src/Cmixin/BusinessDay/Holiday.php:28
        }

        /**
         * @see \Cmixin\BusinessDay\Holiday::isHoliday
         *
         * Checks the date to see if it is a holiday.
         *
         * @return bool
         */
        public function isHoliday($self = null)
        {
            // Content, see src/Cmixin/BusinessDay/Holiday.php:65
        }

        /**
         * @see \Cmixin\BusinessDay\Holiday::getHolidayNamesDictionary
         *
         * Get the holidays in the given language.
         *
         * @param string $locale language
         *
         * @return array
         */
        public function getHolidayNamesDictionary($locale)
        {
            // Content, see src/Cmixin/BusinessDay/Holiday.php:92
        }

        /**
         * @see \Cmixin\BusinessDay\Holiday::getHolidayName
         *
         * Get the name of the current holiday (using the locale given in parameter or the current date locale)
         * or false if it's not a holiday.
         *
         * @param string $locale language ("en" by default)
         *
         * @return string|false
         */
        public function getHolidayName($locale = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/Holiday.php:132
        }

        /**
         * @see \Cmixin\BusinessDay\YearCrawler::getYearHolidays
         *
         * Get the holidays dates for a given year (current year if no parameter given).
         *
         * @param int    $year input year, year of the current instance or context used if omitted, current year used if omitted and called statically
         * @param string $type can be 'string' (to return dates as string) or a class name to returns instances of this class
         *
         * @return array
         */
        public function getYearHolidays($year = null, $type = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/YearCrawler.php:22
        }

        /**
         * @see \Cmixin\BusinessDay\YearCrawler::getYearHolidaysNextFunction
         *
         * Get a next() callback to call to iterate over holidays of a year.
         *
         * @param int    $year input year, year of the current instance or context used if omitted, current year used if omitted and called statically
         * @param string $type can be 'string' (to return dates as string) or a class name to returns instances of this class
         *
         * @return \Closure
         */
        public function getYearHolidaysNextFunction($year = null, $type = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/YearCrawler.php:54
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::standardizeHolidaysRegion
         *
         * Return a standardized region name.
         *
         * @param string $region
         *
         * @return string
         */
        public function standardizeHolidaysRegion($region)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:31
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::setHolidaysRegion
         *
         * Set the holidays region (see src/Cmixin/Holidays for examples).
         *
         * @param string $region
         */
        public function setHolidaysRegion($region)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:52
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::getHolidaysRegion
         *
         * Get the current holidays region.
         *
         * @return null|string
         */
        public function getHolidaysRegion()
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:101
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::getHolidays
         *
         * Get the holidays for the current region selected.
         *
         * @param string $region
         *
         * @return array
         */
        public function getHolidays($region = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:122
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::setHolidays
         *
         * Set the holidays list.
         *
         * @param string $region
         * @param array  $holidays
         */
        public function setHolidays($region, $holidays)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:150
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::resetHolidays
         *
         * Reset the holidays list.
         */
        public function resetHolidays()
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:170
        }

        public function initializeHolidaysRegion()
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:195
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::pushHoliday
         *
         * Push a holiday to the holidays list of a region.
         *
         * @param string          $region    region where the holiday is observed.
         * @param string|\Closure $holiday   date or closure that get the year as parameter and returns the date
         * @param string          $holidayId optional holiday ID
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface|null
         */
        public function pushHoliday($region, $holiday, $holidayId = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:218
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::setHolidayName
         *
         * Set/change the name of holiday by ID for a given language (or a list of languages).
         *
         * @param string       $holidayKey holiday ID (identifier key)
         * @param string|array $language   language 2-chars code (or an array with languages codes as keys and new names for each language as value).
         * @param string       $name       new name (ignored if $language is an array)
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface|null
         */
        public function setHolidayName($holidayKey = null, $language = null, $name = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:252
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::addHoliday
         *
         * Add a holiday to the holidays list of a region and optionally init its ID, name and observed state.
         *
         * @param string          $region    region where the holiday is observed.
         * @param string|\Closure $holiday   date or closure that get the year as parameter and returns the date
         * @param string          $holidayId optional holiday ID
         * @param string          $name      optional name
         * @param bool            $observed  optional observed state
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface|null
         */
        public function addHoliday($region, $holiday, $holidayId = null, $name = null, $observed = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:291
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::unpackHoliday
         *
         * Unpack a holiday array definition.
         *
         * @param array  &$holiday
         * @param string &$name
         * @param bool   &$observed
         *
         * @return array
         */
        public function unpackHoliday($holiday, $name = null, $observed = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:329
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::checkHoliday
         *
         * Check a holiday definition and unpack it if it's an array.
         *
         * @param array  &$holiday
         * @param string $holidayId
         * @param string &$name
         * @param bool   &$observed
         *
         * @return array
         */
        public function checkHoliday($holiday, $holidayId, $name = null, $observed = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:369
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::addHolidays
         *
         * Add a holiday to the holidays list of a region and optionally init their IDs, names and observed states (if provided as array-definitions).
         *
         * @param string $region
         * @param array  $holidays
         */
        public function addHolidays($region, $holidays)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:401
        }

        public function enable()
        {
            // Content, see src/Cmixin/BusinessDay/MixinBase.php:29
        }

        /**
         * @see \Cmixin\BusinessDay\MixinBase::getThisOrToday
         *
         * Return current context $this or Carbon::today() if called statically.
         *
         * @param \Carbon\CarbonInterface $self
         * @param \Carbon\CarbonInterface $context
         *
         * @return \Carbon\CarbonInterface|\Carbon\Carbon|\Carbon\CarbonImmutable
         */
        public function getThisOrToday($self, $context)
        {
            // Content, see src/Cmixin/BusinessDay/MixinBase.php:71
        }

        /**
         * @see \Cmixin\BusinessDay\MixinBase::isDateTimeInstance
         *
         * Return true if the given value is a DateTime or DateTimeInterface.
         *
         * @param mixed $value
         *
         * @return bool
         */
        public function isDateTimeInstance($value)
        {
            // Content, see src/Cmixin/BusinessDay/MixinBase.php:96
        }

        /**
         * @see \Cmixin\BusinessDay\MixinBase::swapDateTimeParam
         *
         * Store a first variable as Carbon instance into the second variable if the first one is a date.
         *
         * @param mixed $date         First variable to check if it's a date (DateTime or DateTimeInterface)
         * @param mixed $target       Target variable that will be replaced by the first one if it's a date
         * @param mixed $defaultValue Value to store in the first variable if it's a date
         *
         * @return array the new pair of variables
         */
        public function swapDateTimeParam($date, $target, $defaultValue)
        {
            // Content, see src/Cmixin/BusinessDay/MixinBase.php:117
        }
    }
}

namespace Illuminate\Support\Facades
{
    class Date
    {
        /**
         * @see \Cmixin\BusinessDay::addBusinessDays
         *
         * Add a given number of business days to the current date.
         *
         * @param int $days
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        public function addBusinessDays($days = 1, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:28
        }

        /**
         * @see \Cmixin\BusinessDay::addBusinessDay
         *
         * Add one business day to the current date.
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        public function addBusinessDay($days = 1, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:28
        }

        /**
         * @see \Cmixin\BusinessDay::subBusinessDays
         *
         * Subtract a given number of business days to the current date.
         *
         * @param int $days
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        public function subBusinessDays($days = 1, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:28
        }

        /**
         * @see \Cmixin\BusinessDay::subtractBusinessDays
         *
         * Subtract a given number of business days to the current date.
         *
         * @param int $days
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        public function subtractBusinessDays($days = 1, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:28
        }

        /**
         * @see \Cmixin\BusinessDay::subBusinessDay
         *
         * Subtract one business day to the current date.
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        public function subBusinessDay($days = 1, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:28
        }

        /**
         * @see \Cmixin\BusinessDay::subtractBusinessDay
         *
         * Subtract one business day to the current date.
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        public function subtractBusinessDay($days = 1, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:28
        }

        /**
         * @see \Cmixin\BusinessDay::diffInBusinessDays
         *
         * Returns the difference between 2 dates in business days.
         *
         * @param \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface $other other date
         *
         * @return int
         */
        public function diffInBusinessDays($other = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:149
        }

        /**
         * @see \Cmixin\BusinessDay::getBusinessDaysInMonth
         *
         * Get the number of business days in the current month.
         *
         * @return int
         */
        public function getBusinessDaysInMonth($self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:177
        }

        /**
         * @see \Cmixin\BusinessDay::getMonthBusinessDays
         *
         * Get list of date objects for each business day in the current month.
         *
         * @return array
         */
        public function getMonthBusinessDays($self = null)
        {
            // Content, see src/Cmixin/BusinessDay.php:202
        }

        /**
         * @see \Cmixin\BusinessDay\BusinessCalendar::isBusinessDay
         *
         * Checks the date to see if it is a business day (neither a weekend day nor a holiday).
         *
         * @return bool
         */
        public function isBusinessDay($self = null)
        {
            // Content, see src/Cmixin/BusinessDay/BusinessCalendar.php:21
        }

        /**
         * @see \Cmixin\BusinessDay\BusinessCalendar::nextBusinessDay
         *
         * Sets the date to the next business day (neither a weekend day nor a holiday).
         *
         * @return bool
         */
        public function nextBusinessDay($self = null)
        {
            // Content, see src/Cmixin/BusinessDay/BusinessCalendar.php:47
        }

        /**
         * @see \Cmixin\BusinessDay\BusinessCalendar::currentOrNextBusinessDay
         *
         * Sets the date to the current or next business day (neither a weekend day nor a holiday).
         *
         * @return bool
         */
        public function currentOrNextBusinessDay($self = null)
        {
            // Content, see src/Cmixin/BusinessDay/BusinessCalendar.php:77
        }

        /**
         * @see \Cmixin\BusinessDay\BusinessCalendar::previousBusinessDay
         *
         * Sets the date to the previous business day (neither a weekend day nor a holiday).
         *
         * @return bool
         */
        public function previousBusinessDay($self = null)
        {
            // Content, see src/Cmixin/BusinessDay/BusinessCalendar.php:47
        }

        /**
         * @see \Cmixin\BusinessDay\BusinessCalendar::currentOrPreviousBusinessDay
         *
         * Sets the date to the current or previous business day.
         *
         * @return bool
         */
        public function currentOrPreviousBusinessDay($self = null)
        {
            // Content, see src/Cmixin/BusinessDay/BusinessCalendar.php:77
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::setObservedHolidaysZone
         *
         * Set the selected zone for observed holidays. So next observe methods will be saved and considered in this
         * given custom zone.
         *
         * @param string $zone
         *
         * @return $this|null
         */
        public function setObservedHolidaysZone($zone, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:40
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::getObservedHolidaysZone
         *
         * Get the selected zone for observed holidays.
         *
         * @return string|null
         */
        public function getObservedHolidaysZone()
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:61
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::setHolidayObserveStatus
         *
         * Set a holiday as observed/unobserved in the selected zone.
         *
         * @param string $holidayId ID key of the holiday
         * @param bool   $observed  observed state
         *
         * @return $this|null
         */
        public function setHolidayObserveStatus($holidayId, $observed, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:84
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::getObserveHolidayMethod
         *
         * Set a holiday as observed/unobserved in the selected zone (can take array of holidays).
         *
         * @param string|array $holidayId ID key of the holiday
         * @param bool         $observed  observed state
         *
         * @return $this|null
         */
        public function getObserveHolidayMethod($holidayId = null, $observed = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:119
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::observeHoliday
         *
         * Set a holiday as observed in the selected zone.
         *
         * @param string $holidayId ID key of the holiday
         *
         * @return $this|null
         */
        public function observeHoliday($holidayId = null, $observed = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:119
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::unobserveHoliday
         *
         * Set a holiday as not observed in the selected zone.
         *
         * @param string $holidayId ID key of the holiday
         *
         * @return $this|null
         */
        public function unobserveHoliday($holidayId = null, $observed = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:119
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::observeHolidays
         *
         * Set a holiday as observed in the selected zone.
         *
         * @param array $holidayIds ID keys of the holidays
         *
         * @return $this|null
         */
        public function observeHolidays($holidayId = null, $observed = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:119
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::unobserveHolidays
         *
         * Set a holiday as not observed in the selected zone.
         *
         * @param array $holidayIds ID keys of the holidays
         *
         * @return $this|null
         */
        public function unobserveHolidays($holidayId = null, $observed = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:119
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::observeAllHolidays
         *
         * Set all holidays as observed in the selected zone.
         *
         * @return $this|null
         */
        public function observeAllHolidays($holidayId = null, $observed = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:119
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::unobserveAllHolidays
         *
         * Set all holidays as observed in the selected zone.
         *
         * @return $this|null
         */
        public function unobserveAllHolidays($holidayId = null, $observed = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:119
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::checkObservedHoliday
         *
         * Check if a given holiday ID is observed in the selected zone.
         *
         * @param string|false|null $holidayId
         *
         * @return bool
         */
        public function checkObservedHoliday($holidayId = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:254
        }

        /**
         * @see \Cmixin\BusinessDay\HolidayObserver::isObservedHoliday
         *
         * Checks the date to see if it is a holiday observed in the selected zone.
         *
         * @param string $holidayId holiday ID to check (current date used instead if omitted)
         *
         * @return bool
         */
        public function isObservedHoliday($holidayId = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidayObserver.php:288
        }

        /**
         * @see \Cmixin\BusinessDay\Holiday::getHolidayId
         *
         * Get the identifier of the current holiday or false if it's not a holiday.
         *
         * @return string|false
         */
        public function getHolidayId($self = null)
        {
            // Content, see src/Cmixin/BusinessDay/Holiday.php:28
        }

        /**
         * @see \Cmixin\BusinessDay\Holiday::isHoliday
         *
         * Checks the date to see if it is a holiday.
         *
         * @return bool
         */
        public function isHoliday($self = null)
        {
            // Content, see src/Cmixin/BusinessDay/Holiday.php:65
        }

        /**
         * @see \Cmixin\BusinessDay\Holiday::getHolidayNamesDictionary
         *
         * Get the holidays in the given language.
         *
         * @param string $locale language
         *
         * @return array
         */
        public function getHolidayNamesDictionary($locale)
        {
            // Content, see src/Cmixin/BusinessDay/Holiday.php:92
        }

        /**
         * @see \Cmixin\BusinessDay\Holiday::getHolidayName
         *
         * Get the name of the current holiday (using the locale given in parameter or the current date locale)
         * or false if it's not a holiday.
         *
         * @param string $locale language ("en" by default)
         *
         * @return string|false
         */
        public function getHolidayName($locale = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/Holiday.php:132
        }

        /**
         * @see \Cmixin\BusinessDay\YearCrawler::getYearHolidays
         *
         * Get the holidays dates for a given year (current year if no parameter given).
         *
         * @param int    $year input year, year of the current instance or context used if omitted, current year used if omitted and called statically
         * @param string $type can be 'string' (to return dates as string) or a class name to returns instances of this class
         *
         * @return array
         */
        public function getYearHolidays($year = null, $type = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/YearCrawler.php:22
        }

        /**
         * @see \Cmixin\BusinessDay\YearCrawler::getYearHolidaysNextFunction
         *
         * Get a next() callback to call to iterate over holidays of a year.
         *
         * @param int    $year input year, year of the current instance or context used if omitted, current year used if omitted and called statically
         * @param string $type can be 'string' (to return dates as string) or a class name to returns instances of this class
         *
         * @return \Closure
         */
        public function getYearHolidaysNextFunction($year = null, $type = null, $self = null)
        {
            // Content, see src/Cmixin/BusinessDay/YearCrawler.php:54
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::standardizeHolidaysRegion
         *
         * Return a standardized region name.
         *
         * @param string $region
         *
         * @return string
         */
        public function standardizeHolidaysRegion($region)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:31
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::setHolidaysRegion
         *
         * Set the holidays region (see src/Cmixin/Holidays for examples).
         *
         * @param string $region
         */
        public function setHolidaysRegion($region)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:52
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::getHolidaysRegion
         *
         * Get the current holidays region.
         *
         * @return null|string
         */
        public function getHolidaysRegion()
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:101
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::getHolidays
         *
         * Get the holidays for the current region selected.
         *
         * @param string $region
         *
         * @return array
         */
        public function getHolidays($region = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:122
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::setHolidays
         *
         * Set the holidays list.
         *
         * @param string $region
         * @param array  $holidays
         */
        public function setHolidays($region, $holidays)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:150
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::resetHolidays
         *
         * Reset the holidays list.
         */
        public function resetHolidays()
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:170
        }

        public function initializeHolidaysRegion()
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:195
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::pushHoliday
         *
         * Push a holiday to the holidays list of a region.
         *
         * @param string          $region    region where the holiday is observed.
         * @param string|\Closure $holiday   date or closure that get the year as parameter and returns the date
         * @param string          $holidayId optional holiday ID
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface|null
         */
        public function pushHoliday($region, $holiday, $holidayId = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:218
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::setHolidayName
         *
         * Set/change the name of holiday by ID for a given language (or a list of languages).
         *
         * @param string       $holidayKey holiday ID (identifier key)
         * @param string|array $language   language 2-chars code (or an array with languages codes as keys and new names for each language as value).
         * @param string       $name       new name (ignored if $language is an array)
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface|null
         */
        public function setHolidayName($holidayKey = null, $language = null, $name = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:252
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::addHoliday
         *
         * Add a holiday to the holidays list of a region and optionally init its ID, name and observed state.
         *
         * @param string          $region    region where the holiday is observed.
         * @param string|\Closure $holiday   date or closure that get the year as parameter and returns the date
         * @param string          $holidayId optional holiday ID
         * @param string          $name      optional name
         * @param bool            $observed  optional observed state
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface|null
         */
        public function addHoliday($region, $holiday, $holidayId = null, $name = null, $observed = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:291
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::unpackHoliday
         *
         * Unpack a holiday array definition.
         *
         * @param array  &$holiday
         * @param string &$name
         * @param bool   &$observed
         *
         * @return array
         */
        public function unpackHoliday($holiday, $name = null, $observed = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:329
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::checkHoliday
         *
         * Check a holiday definition and unpack it if it's an array.
         *
         * @param array  &$holiday
         * @param string $holidayId
         * @param string &$name
         * @param bool   &$observed
         *
         * @return array
         */
        public function checkHoliday($holiday, $holidayId, $name = null, $observed = null)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:369
        }

        /**
         * @see \Cmixin\BusinessDay\HolidaysList::addHolidays
         *
         * Add a holiday to the holidays list of a region and optionally init their IDs, names and observed states (if provided as array-definitions).
         *
         * @param string $region
         * @param array  $holidays
         */
        public function addHolidays($region, $holidays)
        {
            // Content, see src/Cmixin/BusinessDay/HolidaysList.php:401
        }

        public function enable()
        {
            // Content, see src/Cmixin/BusinessDay/MixinBase.php:29
        }

        /**
         * @see \Cmixin\BusinessDay\MixinBase::getThisOrToday
         *
         * Return current context $this or Carbon::today() if called statically.
         *
         * @param \Carbon\CarbonInterface $self
         * @param \Carbon\CarbonInterface $context
         *
         * @return \Carbon\CarbonInterface|\Carbon\Carbon|\Carbon\CarbonImmutable
         */
        public function getThisOrToday($self, $context)
        {
            // Content, see src/Cmixin/BusinessDay/MixinBase.php:71
        }

        /**
         * @see \Cmixin\BusinessDay\MixinBase::isDateTimeInstance
         *
         * Return true if the given value is a DateTime or DateTimeInterface.
         *
         * @param mixed $value
         *
         * @return bool
         */
        public function isDateTimeInstance($value)
        {
            // Content, see src/Cmixin/BusinessDay/MixinBase.php:96
        }

        /**
         * @see \Cmixin\BusinessDay\MixinBase::swapDateTimeParam
         *
         * Store a first variable as Carbon instance into the second variable if the first one is a date.
         *
         * @param mixed $date         First variable to check if it's a date (DateTime or DateTimeInterface)
         * @param mixed $target       Target variable that will be replaced by the first one if it's a date
         * @param mixed $defaultValue Value to store in the first variable if it's a date
         *
         * @return array the new pair of variables
         */
        public function swapDateTimeParam($date, $target, $defaultValue)
        {
            // Content, see src/Cmixin/BusinessDay/MixinBase.php:117
        }
    }
}
