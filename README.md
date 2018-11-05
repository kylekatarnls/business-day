# business-day
Carbon mixin to handle business days

[![Latest Stable Version](https://poser.pugx.org/cmixin/business-day/v/stable.png)](https://packagist.org/packages/cmixin/business-day)
[![Build Status](https://travis-ci.org/kylekatarnls/business-day.svg?branch=master)](https://travis-ci.org/kylekatarnls/business-day)
[![Code Climate](https://codeclimate.com/github/kylekatarnls/business-day/badges/gpa.svg)](https://codeclimate.com/github/kylekatarnls/business-day)
[![Test Coverage](https://codeclimate.com/github/kylekatarnls/business-day/badges/coverage.svg)](https://codeclimate.com/github/kylekatarnls/business-day/coverage)
[![Issue Count](https://codeclimate.com/github/kylekatarnls/business-day/badges/issue_count.svg)](https://codeclimate.com/github/kylekatarnls/business-day)
[![StyleCI](https://styleci.io/repos/129502391/shield?branch=master)](https://styleci.io/repos/129502391)

## Install

```
composer require cmixin/business-day
```

## Usage

First load the mixin in some global bootstrap place of your app:

```php
<?php

use Cmixin\BusinessDay;

BusinessDay::enable('Carbon\Carbon');
// Or if you use Laravel:
// BusinessDay::enable('Illuminate\Support\Carbon');
```

Business days methods are now available on any Carbon instance
used anywhere later.

### Configure holidays

You can set different holidays lists for different regions
(to handle different countries, enterprises, etc.)

We provide a some holidays lists as example that work
out of the box:
https://github.com/kylekatarnls/business-day/tree/master/src/Cmixin/Holidays

A holidays list file is a PHP file that return an array,
each item of the array represent a holiday of the year.

It can be a fixed date such as `'25/12'` for Christmas or
a closure that will calculate the date for the given year.

**Warning**: the format is **DD/MM**, day slash month,
both on 2 digits, `'4/05'`, `'04/5'` or `'4/5'` will
just be ignored. Only `'04/05'` will work and this
is the 4th of May (*May the Force be with you*), not
the 5th of April.

For example the Martin Luther King Jr. Day (which fall
at the third monday of January) can be calculated this way:
```php
function ($year) {
    $date = new DateTime("third monday of january $year");

    return $date->format('d/m');
}
```
The same goes for closures, they strictly must return
strings with **DD/MM** format.

Other example for Easter monday:
```php
function ($year) {
    $days = easter_days($year) + 1;
    $date = new DateTime("$year-03-21 +$days days");

    return $date->format('d/m');
}
```

#### setHolidaysRegion

To select the set of holidays of a region, use:
```php
Carbon::parse('2000-12-25 00:00:00')->isHoliday(); // false
Carbon::setHolidaysRegion('us-national');
Carbon::parse('2000-12-25 00:00:00')->isHoliday(); // true
```

This will select our national preset for USA (only
holidays common to all states).

Before selecting a region the list of holidays is
empty so `isHoliday` will return `false` for any date.

#### getHolidays

This method allow you to get the holiday list for a
given region passed in argument, if no argument given
the list for the current selected region is returned.

```php
Carbon::getHolidays('us-national'); // raw us-national holidays list
Carbon::setHolidaysRegion('fr-national');
Carbon::getHolidays(); // raw fr-national holidays list
```

#### setHolidays

It's how you can set your own holidays lists:

```php
Carbon::setHolidays('us-il', array_merge(
    Carbon::getHolidays('us-national'),
    array(
        // Presidents' Day
        'presidents-day' => function ($year) {
            $date = new DateTime("third monday of february $year");
    
            return $date->format('d/m');
        },
        // Columbus Day
        'columbus-day' => function ($year) {
            $date = new DateTime("second monday of october $year");
    
            return $date->format('d/m');
        },
        // Day after Thanksgiving
        'thanksgiving-next-day' => function ($year) {
            $date = new DateTime("fourth thursday of november $year +1 day");
    
            return $date->format('d/m');
        },
    )
));
Carbon::setHolidays('my-enterprise', array_merge(
    Carbon::getHolidays('us-is'),
    array(
        // Lincoln's Birthday
        'lincolns-birthday' => '12/02',
    )
));
// Then when you select my-enterprise, all us-national,
// us-il and my-enterprise days are enabled
Carbon::setHolidaysRegion('my-enterprise');
```

You can also pass deep array to `setHolidays` to set in the same call holidays dates and either observed flags, names
(in different languages) or both:
```php
Carbon::setHolidays('my-enterprise', array(
    'lincolns-birthday' => array(
        'date'     => '12/02',
        'observed' => true,
        'name'     => array(
            'en' => "Lincoln's Birthday",
            'fr' => 'Anniversaire du Président Lincoln',
        ),
    ),
));
```

#### addHolidays

While setHolidays replace the whole holidays list for a given region, addHolidays
append holidays to the current list.

```php
Carbon::addHolidays('my-list', array(
    'poney'        => '20/01',
    'swimmingpool' => '21/01',
)));
Carbon::addHolidays('my-list', array(
    'boss-birthday'          => '10/02',
    'boss-birthday-next-day' => '11/02',
)));
Carbon::getHolidays('my-list') // contains 20/01, 21/01, 10/02 and 11/02
```

As for `setHolidays`, `addHolidays` handle deep arrays using date, observed and name keys.

#### resetHolidays

Reset all holidays and region previously set.

#### isHoliday

Returns `true` if the date (Carbon instance) is a holiday (in the list
of the selected region), `false` else.

```php
Carbon::setHolidaysRegion('us-national');
Carbon::parse('2018-01-15')->isHoliday() // true for 2018
Carbon::parse('2018-01-21')->isHoliday() // false for 2018
Carbon::parse('2019-01-15')->isHoliday() // false for 2019
Carbon::parse('2019-01-21')->isHoliday() // true for 2019
Carbon::isHoliday() // true if today is a holiday
```

#### getHolidayId

Same as `isHoliday` but returns a string id for the given holiday, so you can target specific holidays.

```php
Carbon::setHolidaysRegion('us-national');
Carbon::parse('2018-12-25')->getHolidayId() // "christmas"
Carbon::parse('2018-01-15')->getHolidayId() // "mlk-day"
// Returns false if the day is not a holiday
Carbon::parse('2018-01-21')->getHolidayId() // false

$nextWeekHoliday = Carbon::today()->addWeek()->getHolidayId();
if ($nextWeekHoliday === 'easter' or $nextWeekHoliday === 'christmas') {
  echo 'Time to get chocolates.';
}
```

#### getHolidayName

Returns the name of the holiday in the current locale (or English by default) or false if the day is not a holiday.

```php
Carbon::setHolidaysRegion('fr-national');
Carbon::parse('2018-12-25')->getHolidayName() // "Christmas"
Carbon::parse('2018-12-25')->getHolidayName('fr') // "Noël"

Carbon::setLocale('nl');
Carbon::parse('2018-01-15')->getHolidayName() // "Eerste Kerstdag"

// If the name is not translated in business-day
Carbon::setLocale('de');
Carbon::parse('2018-01-15')->getHolidayName() // "Christmas"

// With Carbon 2, you can use local locale:
Carbon::parse('2018-01-15')->locale('sl')->getHolidayName() // "Božič"
```

#### isBusinessDay

Returns `true` if the date (Carbon instance) is nor a week-end day neither
a holiday, `false` else. Week-end days can be configured (see
https://carbon.nesbot.com/docs/#weekend).

```php
Carbon::setHolidaysRegion('us-national');
Carbon::parse('2018-01-15')->isBusinessDay() // false for 2018 (Martin Luther King Jr. Day, sleep)
Carbon::parse('2018-01-21')->isBusinessDay() // false for 2018 (sunday is week-end by default, sleep)
Carbon::parse('2019-01-15')->isBusinessDay() // true for 2019 (tuesday, nothing special, go to work)
Carbon::parse('2019-01-21')->isBusinessDay() // false for 2019 (Martin Luther King Jr. Day, sleep)
Carbon::isBusinessDay() // true if today is a business day
```

#### nextBusinessDay

Add days to the date (Carbon instance) to jump to the next business day (skipping
holidays and week-ends).
```php
Carbon::setHolidaysRegion('us-national');
echo Carbon::parse('2018-01-12')->nextBusinessDay()->format('Y-m-d'); // 2018-01-16
echo Carbon::parse('2018-01-13')->nextBusinessDay()->format('Y-m-d'); // 2018-01-16
echo Carbon::parse('2018-01-15')->nextBusinessDay()->format('Y-m-d'); // 2018-01-16
echo Carbon::parse('2018-01-16')->nextBusinessDay()->format('Y-m-d'); // 2018-01-17
echo Carbon::parse('2018-01-12 12:30:00')->nextBusinessDay()->format('Y-m-d H:i'); // 2018-01-16 12:30 (time is kept)
// If you don't want to keep the time, just use ->startOfDay()
echo Carbon::nextBusinessDay()->format('Y-m-d'); // returns the next business day date after today (midnight)
```

#### previousBusinessDay

Sub days to the date (Carbon instance) to jump to the previous business day (skipping
holidays and week-ends).
```php
Carbon::setHolidaysRegion('us-national');
echo Carbon::parse('2018-01-12')->previousBusinessDay()->format('Y-m-d'); // 2018-01-11
echo Carbon::parse('2018-01-13')->previousBusinessDay()->format('Y-m-d'); // 2018-01-12
echo Carbon::parse('2018-01-15')->previousBusinessDay()->format('Y-m-d'); // 2018-01-12
echo Carbon::parse('2018-01-16')->previousBusinessDay()->format('Y-m-d'); // 2018-01-12
echo Carbon::parse('2018-01-15 12:30:00')->previousBusinessDay()->format('Y-m-d H:i'); // 2018-01-12 12:30 (time is kept)
// If you don't want to keep the time, just use ->startOfDay()
echo Carbon::previousBusinessDay()->format('Y-m-d'); // returns the previous business day date before today (midnight)
```

#### currentOrNextBusinessDay

Returns the current date (Carbon instance) if it's a business day, else
add days to jump to the next business day (skipping
holidays and week-ends).
```php
Carbon::setHolidaysRegion('us-national');
echo Carbon::parse('2018-01-12')->currentOrNextBusinessDay()->format('Y-m-d'); // 2018-01-12
echo Carbon::parse('2018-01-13')->currentOrNextBusinessDay()->format('Y-m-d'); // 2018-01-16
echo Carbon::parse('2018-01-15')->currentOrNextBusinessDay()->format('Y-m-d'); // 2018-01-16
echo Carbon::parse('2018-01-16')->currentOrNextBusinessDay()->format('Y-m-d'); // 2018-01-16
echo Carbon::parse('2018-01-13 12:30:00')->currentOrNextBusinessDay()->format('Y-m-d H:i'); // 2018-01-16 12:30 (time is kept)
// If you don't want to keep the time, just use ->startOfDay()
echo Carbon::currentOrNextBusinessDay() // equivalent to Carbon::today()->currentOrNextBusinessDay()
```

#### currentOrPreviousBusinessDay

Returns the current date (Carbon instance) if it's a business day, else
sub days to jump to the previous business day (skipping
holidays and week-ends).
```php
Carbon::setHolidaysRegion('us-national');
echo Carbon::parse('2018-01-12')->currentOrPreviousBusinessDay()->format('Y-m-d'); // 2018-01-12
echo Carbon::parse('2018-01-13')->currentOrPreviousBusinessDay()->format('Y-m-d'); // 2018-01-12
echo Carbon::parse('2018-01-15')->currentOrPreviousBusinessDay()->format('Y-m-d'); // 2018-01-12
echo Carbon::parse('2018-01-16')->currentOrPreviousBusinessDay()->format('Y-m-d'); // 2018-01-16
echo Carbon::parse('2018-01-13 12:30:00')->currentOrPreviousBusinessDay()->format('Y-m-d H:i'); // 2018-01-16 12:30 (time is kept)
// If you don't want to keep the time, just use ->startOfDay()
echo Carbon::currentOrPreviousBusinessDay() // equivalent to Carbon::today()->currentOrPreviousBusinessDay()
```

#### addBusinessDays

Add days to the date (Carbon instance) skipping holidays and week-ends.
```php
Carbon::setHolidaysRegion('us-national');
echo Carbon::parse('2018-01-10')->addBusinessDays(4)->format('Y-m-d'); // 2018-01-17
echo Carbon::parse('2018-01-13')->addBusinessDays()->format('Y-m-d'); // 2018-01-16 add 1 business day by default
echo Carbon::addBusinessDays(6)->format('Y-m-d'); // returns the date 6 business days after today (midnight)
```

#### addBusinessDay

Alias addBusinessDays.

#### subBusinessDays

Sub days to the date (Carbon instance) skipping holidays and week-ends.
```php
Carbon::setHolidaysRegion('us-national');
echo Carbon::parse('2018-01-17')->subBusinessDays(4)->format('Y-m-d'); // 2018-01-12
echo Carbon::parse('2018-01-15')->subBusinessDays()->format('Y-m-d'); // 2018-01-12 sub 1 business day by default
echo Carbon::subBusinessDays(5)->format('Y-m-d'); // returns the date 5 business days date before today (midnight)
```

#### subBusinessDay

Alias subBusinessDays.

## Contribute

The scope of this library is to date business date and time utilities to Carbon,
if you think of a feature in this scope, feel free to submit a pull-request.

We will also happily merge any holidays file matching an official country, state
or region holidays list.

## Credits

Based on the work of [Christopher "rmblstrp"](https://github.com/rmblstrp),
see [Carbon PR #706](https://github.com/briannesbitt/Carbon/pull/706)