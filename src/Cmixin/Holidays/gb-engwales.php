<?php

return array(
    'new-year'      => function ($year) {
        $date = DateTime::createFromFormat('U', (string)strtotime("$year/01/01"));

        if (date('N', strtotime("$year/01/01")) == 6) {
            $date->add(new DateInterval('P2D'));
        } elseif (date('N', strtotime("$year/01/01")) == 7) {
            $date->add(new DateInterval('P1D'));
        }

        return $date->format('d/m');
    },
    'easter-monday' => function ($year) {
        $days = easter_days($year) + 1;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    'summer'   => function ($year) {
        $date = DateTime::createFromFormat('U', (string)strtotime("last Monday of August $year"));

        return $date->format('d/m');
    },
);
