<?php

return array(
    '01/01', // Nieuwjaarsdag
    function ($year) { // Paaszondag
        $days = easter_days($year);
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    function ($year) { // Paasmaandag
        $days = easter_days($year) + 1;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    function ($year) { // Koningsdag
        $date = new DateTime("$year-04-27");
        if ($date->format('w') === '0') {
            $date->sub(new DateInterval('P1D'));
        }

        return $date->format('d/m');
    },
    function ($year) { // Bevrijdingsdag
        if ($year % 5 === 0) {
            $date = new DateTime("$year-05-05");

            return $date->format('d/m');
        }
    },
    function ($year) { // Hemelvaart
        $days = easter_days($year) + 39;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    function ($year) { // Pinksterzondag
        $days = easter_days($year) + 49;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    function ($year) { // Pinkstermaandag
        $days = easter_days($year) + 50;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    '25/12', // Eerste Kerstdag
    '26/12', // Tweede Kerstdag
);
