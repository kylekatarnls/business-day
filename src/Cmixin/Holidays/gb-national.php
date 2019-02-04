<?php

return array(
    'good-friday' => function ($year) {
        $days = easter_days($year) - 2;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    'early-may'    => function ($year) {
        $date = DateTime::createFromFormat('U', strtotime("first Monday of May $year"));

        return $date->format('d/m');
    },
    'spring' => function ($year) {
        $date = DateTime::createFromFormat('U', strtotime("last Monday of May $year"));

        return $date->format('d/m');
    },
    'christmas'      => function ($year) {
        $date = DateTime::createFromFormat('U', strtotime("$year/12/25"));

        if (date('N', strtotime("$year/12/25")) >= 6) {
            $date->add(new DateInterval('P2D'));
        }

        return $date->format('d/m');
    },
    'boxing-day'     => function ($year) {
        $date = DateTime::createFromFormat('U', strtotime("$year/12/26"));

        if (date('N', strtotime("$year/12/26")) >= 6) {
            $date->add(new DateInterval('P2D'));
        }

        return $date->format('d/m');
    },
);
