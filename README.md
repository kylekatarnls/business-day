# business-day
Carbon mixin to handle business days

[![Latest Stable Version](https://poser.pugx.org/cmixin/business-day/v/stable.png)](https://packagist.org/packages/cmixin/business-day)
[![Build Status](https://travis-ci.org/kylekatarnls/business-day.svg?branch=master)](https://travis-ci.org/kylekatarnls/business-day)
[![Code Climate](https://codeclimate.com/github/kylekatarnls/business-day/badges/gpa.svg)](https://codeclimate.com/github/kylekatarnls/business-day)
[![Test Coverage](https://codeclimate.com/github/kylekatarnls/business-day/badges/coverage.svg)](https://codeclimate.com/github/kylekatarnls/business-day/coverage)
[![Issue Count](https://codeclimate.com/github/kylekatarnls/business-day/badges/issue_count.svg)](https://codeclimate.com/github/kylekatarnls/business-day)
[![StyleCI](https://styleci.io/repos/129502391/shield?branch=master)](https://styleci.io/repos/129502391)

[Professionally supported nesbot/carbon is now available](https://tidelift.com/subscription/pkg/packagist-nesbot-carbon?utm_source=packagist-nesbot-carbon&utm_medium=referral&utm_campaign=readme)

## Install

```shell
composer require cmixin/business-day
```

## Usage

First load the mixin in some global bootstrap place of your app:

```php
<?php

use Cmixin\BusinessDay;

// You can select one of our official list
$baseList = 'us-national'; // or region such as 'us-il'

// You can add/remove days (optional):
$additionalHolidays = array(
    'independence-day' => null, // Even if it's holiday, you can force it to null to make your business open
    'boss-birthday'    => '09-26', // Close the office on September 26th
    'julian-christmas' => '= julian 12-25', // We support many calendars such as the Julian calendar
    // We support expressions
    'special-easter'   => '= Tuesday before easter',
    'last-monday'      => '= last Monday of October',
    'conditional'      => '= 02-25 if Tuesday then next Friday', // We support conditions
    // And we support closures:
    'very-special'     => function ($year) {
        if ($year === 2020) {
            return '01-15';
        }

        return '02-15';
    },
);

BusinessDay::enable('Carbon\Carbon', $baseList, $additionalHolidays);
// Or if you use Laravel:
// BusinessDay::enable('Illuminate\Support\Carbon', $baseList, $additionalHolidays);
```

Business days methods are now available on any Carbon instance
used anywhere later.

You can also just enable methods on Carbon then set region/holidays later:
```php
<?php

use Cmixin\BusinessDay;

BusinessDay::enable('Carbon\Carbon');
// Or if you use Laravel:
// BusinessDay::enable('Illuminate\Support\Carbon');
```

### Configure holidays

You can set different holidays lists for different regions
(to handle different countries, enterprises, etc.)

We provide 356 regional holidays lists of 143 countries that work
out of the box in [src/Cmixin/Holidays](https://github.com/kylekatarnls/business-day/tree/master/src/Cmixin/Holidays).

And you can easily customize them and add your own lists.

A holidays list file is a PHP file that return an array,
each item of the array represent a holiday of the year.

It can be a fixed date such as `'12-25'` for Christmas,
a expression starting with `=` like `'third Monday of January'`
or a closure that will calculate the date for the given year.

Expressions support any format the [PHP `DateTime` constructor
supports](http://php.net/manual/en/datetime.formats.php) such as:

- `second Wednesday of July`
- `03-01 - 3 days`

And we add a lot of syntaxical sugar:

- `= easter 3` (Easter + 3 days)
- `= Friday before 01-01` (before/after)
- `= 01-01 if Saturday then next Monday and if Sunday,Monday then next Tuesday` (multiple conditions)
- `= 01-01 if weekend then next Monday` (weekend = Saturday or Sunday)
- `= 01-01 substitute` (substitute = shift the day to the next day that is not Saturday, Sunday or an other holiday,
it means if you have both `01-01 substitute` and `01-02 substitute`, you're sure to get the 2 holidays not during the
weekend in the right order no matter the days it falls)
- `= orthodox -2` (2 days before Orthodox Easter)
- `= julian 12-25` (Julian calendar)
- `= 02-05 on Monday` holiday only if this is a Monday.
- `= 02-05 not on Monday` not an holiday if this is a Monday.
- `= 02-05 on even years` even years only.
- `= 02-05 on every 5 years since 1999` if year is 1999, 2004, 2009, 2014, etc.

#### setHolidaysRegion

To select the set of holidays of a region, use:
```php
Carbon::parse('2000-12-25 00:00:00')->isHoliday(); // false
Carbon::setHolidaysRegion('us');
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
Carbon::getHolidays('us'); // raw us-national holidays list
Carbon::setHolidaysRegion('fr');
Carbon::getHolidays(); // raw fr-national holidays list
```

#### setHolidays

It's how you can set your own holidays lists:

```php
Carbon::setHolidays('us-il', array_merge(
    Carbon::getHolidays('us-national'),
    array(
        // Presidents' Day
        'presidents-day' => '= third monday of february', // can be a datetime expression
        // Columbus Day
        'columbus-day' => function ($year) { // can be a closure
            $date = new DateTime("second monday of october $year");
    
            return $date->format('d/m');
        },
        // Day after Thanksgiving
        'thanksgiving-next-day' => '= fourth thursday of november $year +1 day', // '$year' will be replaced by current year
    )
));
Carbon::setHolidays('my-enterprise', array_merge(
    Carbon::getHolidays('us-is'),
    array(
        // Lincoln's Birthday
        'lincolns-birthday' => '= 02-12 substitute', // substitute will shift the holiday until it does not fall on Saturday, Sunday or an other holiday
        'company-closed-day' => '= 04-05 if friday then previous wednesday', // custom rules can be applied with if/then (if can be followed by a day name or "weekend")
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

Note: be aware, region has no effect to the holiday language name since some regions have multiple languages.
So to get french names of the french holidays, you need both `Carbon::setHolidaysRegion('fr-national')` and
`Carbon::setLocale('fr_FR')`, the first for the holiday calendar, the second for the language.

#### setHolidayName

Wanna rename a holiday name in a particular language? No problem:

```php
Carbon::parse('2018-12-25')->getHolidayName() // "Christmas"
Carbon::setHolidayName('christmas', 'en', 'Christmas Day');
Carbon::parse('2018-12-25')->getHolidayName() // "Christmas Day"
```

#### isBusinessDay

Returns `true` if the date (Carbon instance) is nor a week-end day neither
a holiday, `false` else. Week-end days can be configured (see
[Carbon documentation weekend section](https://carbon.nesbot.com/docs/#weekend)).

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

#### subBusinessDays or subtractBusinessDays

Sub days to the date (Carbon instance) skipping holidays and week-ends.
```php
Carbon::setHolidaysRegion('us-national');
echo Carbon::parse('2018-01-17')->subBusinessDays(4)->format('Y-m-d'); // 2018-01-12
echo Carbon::parse('2018-01-15')->subBusinessDays()->format('Y-m-d'); // 2018-01-12 sub 1 business day by default
echo Carbon::subBusinessDays(5)->format('Y-m-d'); // returns the date 5 business days date before today (midnight)
```

#### subBusinessDay or subtractBusinessDay

Alias subBusinessDays.

#### setObservedHolidaysZone

You can define isolated zones that can observe different holidays lists. By default the zone is `"default"`.

```php
Carbon::setHolidaysRegion('us-national');

Carbon::setObservedHolidaysZone('Nevada-subsidiary-company');
Carbon::observeAllHolidays();
Carbon::unobserveHoliday('independence-day');

Carbon::setObservedHolidaysZone('Illinois-subsidiary-company');
Carbon::observeAllHolidays();
Carbon::unobserveHolidays(array('labor-day', 'mlk-day'));

Carbon::setObservedHolidaysZone('Nevada-subsidiary-company');
Carbon::parse('2018-01-15')->isObservedHoliday(); // true
Carbon::parse('2018-07-04')->isObservedHoliday(); // false
Carbon::parse('2018-09-03')->isObservedHoliday(); // true

Carbon::setObservedHolidaysZone('Illinois-subsidiary-company');
Carbon::parse('2018-01-15')->isObservedHoliday(); // false
Carbon::parse('2018-07-04')->isObservedHoliday(); // true
Carbon::parse('2018-09-03')->isObservedHoliday(); // false
```

#### getObservedHolidaysZone

Get the current zone used for observed holidays.

```php
Carbon::getObservedHolidaysZone(); // "Illinois-subsidiary-company"
```

#### isObservedHoliday

Return true if the current day is an observed holiday (holiday as per the holidays list in use and if it's
observed as per the current zone).

```php
Carbon::setHolidaysRegion('fr-national');
Carbon::parse('2018-01-15')->isHoliday(); // true
// By default no holiday is observed
Carbon::parse('2018-01-15')->isObservedHoliday(); // false
// Then you can observe a list of holidays
Carbon::unobserveHolidays(array('christmas', 'new-year'));
Carbon::parse('2018-01-15')->isObservedHoliday(); // true
// Observed holidays are kept if you change the holidays region:
Carbon::setHolidaysRegion('us-national');
Carbon::parse('2018-01-15')->isObservedHoliday(); // true
// So you can observe holidays that are not in the current region with no restriction
```

#### observeHoliday

Observe holidays. You can have multiple set of observed days using `setObservedHolidaysZone`.

```php
Carbon::observeHoliday(array('christmas', 'new-year'));
Carbon::observeHoliday('easter');
```

#### observeHolidays

Alias of `observeHoliday`.

```php
Carbon::observeHolidays(array('christmas', 'new-year'));
Carbon::observeHolidays('easter');
```

#### unobserveHoliday

Set holidays as not observed. You can have multiple set of observed days using `setObservedHolidaysZone`.

```php
Carbon::unobserveHoliday(array('christmas', 'new-year'));
Carbon::unobserveHoliday('easter');
```

#### unobserveHolidays

Alias of `unobserveHoliday`.

```php
Carbon::unobserveHolidays(array('christmas', 'new-year'));
Carbon::unobserveHolidays('easter');
```

#### observeAllHolidays

Remove any previous settings for observed days in the current zone, then make every holidays as observed by default.

```php
// Observe every holidays except Easter
Carbon::observeAllHolidays();
Carbon::unobserveHoliday('easter');
```

#### unobserveAllHolidays

Remove any previous settings for observed days in the current zone, then make every holidays as not observed by default.

```php
// Observe only Easter
Carbon::unobserveAllHolidays();
Carbon::observeHoliday('easter');
```

#### diffInBusinessDays

Get the number of open days between 2 dates.
 
```php
$openDays = Carbon::diffInBusinessDays('December 25'); // If only one date, [now] is used as the second date

echo "You have to work $openDays days until Christmas.\n";

$days = Carbon::parse('2019-06-10')->diffInBusinessDays(Carbon::parse('2019-06-18')->endOfDay());

echo "If you ask to leave from 2019-06-10 to 2019-06-18, it will cost to you $days days of paid vacation.\n";
// Note the ->endOfDay() to include the last day of the range
```

#### getBusinessDaysInMonth

Get the number of open days in the current/given month.

```php
echo Carbon::getBusinessDaysInMonth(); // Open days count in the current month
echo Carbon::getBusinessDaysInMonth('2019-06'); // Open days count in June 2019
echo Carbon::parse('2019-06-10')->getBusinessDaysInMonth(); // Can be called from an instance
```

#### getMonthBusinessDays

Get an array of Carbon objects for each open day in the current/given month.

```php
print_r(Carbon::getMonthBusinessDays()); // All open days in the current month
print_r(Carbon::getMonthBusinessDays('2019-06')); // Open days in June 2019
print_r(Carbon::parse('2019-06-10')->getMonthBusinessDays()); // Can be called from an instance
```

## Contribute

The scope of this library is to date business date and time utilities to Carbon,
if you think of a feature in this scope, feel free to submit a pull-request.

We will also happily merge any holidays file matching an official country, state
or region holidays list.

## Credits

Based on the work of [Christopher "rmblstrp"](https://github.com/rmblstrp),
see [Carbon PR #706](https://github.com/briannesbitt/Carbon/pull/706)
