<?php

return [
    '01-01-and-if-sunday-then-next-monday'                   => '= 01-01 substitute',
    '2nd-monday-in-january'                                  => '= second Monday of January',
    '02-11-and-if-sunday-then-next-monday'                   => '= 02-11 substitute',
    '04-29-and-if-sunday-then-next-monday'                   => '= 04-29 substitute',
    '05-03'                                                  => '05-03',
    'substitutes-05-03-if-sunday-then-next-wednesday'        => '= 05-03 if Sunday then next Wednesday',
    '05-04-and-if-sunday-then-next-tuesday'                  => '= 05-04 if Sunday then next Tuesday',
    '05-05-and-if-sunday-then-next-monday'                   => '= 05-05 substitute',
    '3rd-monday-in-july'                                     => '= third Monday of July',
    '08-11-and-if-sunday-then-next-monday'                   => '= 08-11 substitute',
    '3rd-monday-in-september'                                => '= third Monday of September',
    '09-22-if-09-21-and-09-23-is-public-holiday'             => function ($year) {
        $date = new DateTime("third Monday of September $year");

        if ($date->format('d') === '21') {
            return '22/09';
        }
    },
    'substitutes-09-23-if-sunday-then-next-monday'           => '= 09-23 if sunday then next monday',
    '2nd-monday-in-october'                                  => '= second monday of October',
    '11-03-and-if-sunday-then-next-monday'                   => '= 11-03 substitute',
    '11-23-and-if-sunday-then-next-monday'                   => '= 11-23 substitute',
    '12-23-and-if-sunday-then-next-monday'                   => '= 12-23 substitute',
    'march-equinox-in-+09:00-and-if-sunday-then-next-monday' => '= March equinox of +09:00 substitute',
    'september-equinox-in-+09:00'                            => '= September equinox of +09:00',
];
