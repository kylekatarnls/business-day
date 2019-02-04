<?php

return array(
    'new-year'      => function ($year) {
        $date = DateTime::createFromFormat('U', strtotime($year . "/01/01"));

        if (date('N', strtotime($year . "/01/01")) == 6) { $date->add(new DateInterval('P2D')); }
        else if (date('N', strtotime($year . "/01/01")) == 7) { $date->add(new DateInterval('P1D')); }

        return $date->format('d/m');
    },
    'st-patricks' => function ($year) {
        $date = DateTime::createFromFormat('U', strtotime($year . "/03/17"));

        if (date('N', strtotime($year . "/03/17")) == 6) { $date->add(new DateInterval('P2D')); }
        else if (date('N', strtotime($year . "/03/17")) == 7) { $date->add(new DateInterval('P1D')); }

        return $date->format('d/m');
    },
    'easter-monday' => function ($year) {
        $days = easter_days($year) + 1;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    'boyne' => function ($year) {
        $date = DateTime::createFromFormat('U', strtotime($year . "/07/12"));

        if (date('N', strtotime($year . "/07/12")) == 6) { $date->add(new DateInterval('P2D')); }
        else if (date('N', strtotime($year . "/07/12")) == 7) { $date->add(new DateInterval('P1D')); }

        return $date->format('d/m');
    },
    'summer'   => function ($year) {
        $date = DateTime::createFromFormat('U', strtotime("last Monday of August " . $year));

        return $date->format('d/m');
    },
);
