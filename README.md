# business-day
Carbon mixin to handle business days

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

## Credits

Based on the work of [Christopher "rmblstrp"](https://github.com/rmblstrp),
see [Carbon PR #706](https://github.com/briannesbitt/Carbon/pull/706)