<?php

return array(
    'easter-monday' => function ($year) {
        $days = easter_days($year) + 1;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    'summer' => function ($year) {
        $date = new DateTime("last Monday of August $year");

        return $date->format('d/m');
    },
);
