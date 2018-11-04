<?php

return array(
    'new-year' => '01/01', // Nieuwjaarsdag
    // Paaszondag
    'easter'   => function ($year) {
        $days = easter_days($year);
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    // Paasmaandag
    'easter-monday' => function ($year) {
        $days = easter_days($year) + 1;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    // Koningsdag
    'royal-day' => function ($year) {
        $date = new DateTime("$year-04-27");
        if ($date->format('w') === '0') {
            $date->sub(new DateInterval('P1D'));
        }

        return $date->format('d/m');
    },
    // Bevrijdingsdag
    'liberation-day' => function ($year) {
        if ($year % 5 === 0) {
            $date = new DateTime("$year-05-05");

            return $date->format('d/m');
        }
    },
    // Hemelvaart
    'ascension' => function ($year) {
        $days = easter_days($year) + 39;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    // Pinksterzondag
    'pentecost' => function ($year) {
        $days = easter_days($year) + 49;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    // Pinkstermaandag
    'pentecost-monday' => function ($year) {
        $days = easter_days($year) + 50;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    'christmas'          => '25/12', // Eerste Kerstdag
    'christmas-next-day' => '26/12', // Tweede Kerstdag
);
