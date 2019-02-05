<?php

return array(
    'new-year' => function ($year) {
        $date = new DateTime("$year-01-01");

        if ($date->format('N') > 5) {
            $date->modify('next monday');
        }

        return $date->format('d/m');
    },
    'st-patricks' => function ($year) {
        $date = new DateTime("$year-03-17");

        if ($date->format('N') > 5) {
            $date->modify('next monday');
        }

        return $date->format('d/m');
    },
    'easter-monday' => function ($year) {
        $days = easter_days($year) + 1;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    'boyne' => function ($year) {
        $date = new DateTime("$year-07-12");

        if ($date->format('N') > 5) {
            $date->modify('next monday');
        }

        return $date->format('d/m');
    },
    'summer' => function ($year) {
        $date = new DateTime("last Monday of August $year");

        return $date->format('d/m');
    },
);
