<?php

return array(
    'new-year' => '01/01', // Nieuwjaarsdag
    'easter' => function ($year) { // Paaszondag
        $days = easter_days($year);
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    'easter-monday' => function ($year) { // Paasmaandag
        $days = easter_days($year) + 1;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    'royal-day' => function ($year) { // Koningsdag
        $date = new DateTime("$year-04-27");
        if ($date->format('w') === '0') {
            $date->sub(new DateInterval('P1D'));
        }

        return $date->format('d/m');
    },
    'liberation-day' => function ($year) { // Bevrijdingsdag
        if ($year % 5 === 0) {
            $date = new DateTime("$year-05-05");

            return $date->format('d/m');
        }
    },
    'ascension' => function ($year) { // Hemelvaart
        $days = easter_days($year) + 39;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    'pentcost' => function ($year) { // Pinksterzondag
        $days = easter_days($year) + 49;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    'pentcost-monday' => function ($year) { // Pinkstermaandag
        $days = easter_days($year) + 50;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    'christmas' => '25/12', // Eerste Kerstdag
    'christmas-next-day' => '26/12', // Tweede Kerstdag
);
