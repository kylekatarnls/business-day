<?php

return array(
    'new-year'           => '01/01', // Nieuwjaarsdag
    // Paaszondag
    'easter'             => '= easter',
    // Paasmaandag
    'easter-monday'      => '= easter + 1',
    // Koningsdag
    'royal-day'          => '= 04-27 if sunday then -1 day',
    // Bevrijdingsdag
    'liberation-day'     => function ($year) {
        if ($year % 5 === 0) {
            $date = new DateTime("$year-05-05");

            return $date->format('d/m');
        }
    },
    // Hemelvaart
    'ascension'          => '= easter + 39',
    // Pinksterzondag
    'pentecost'          => '= easter + 49',
    // Pinkstermaandag
    'pentecost-monday'   => '= easter + 50',
    'christmas'          => '25/12', // Eerste Kerstdag
    'christmas-next-day' => '26/12', // Tweede Kerstdag
);
