<?php

return array(
    'new-year' => function ($year) {
        $date = new DateTime("$year-01-01");

        if ($date->format('N') > 5) {
            $date->modify('next monday');
        }

        return $date->format('d/m');
    },
    'good-friday' => function ($year) {
        $days = easter_days($year) - 2;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    'early-may' => function ($year) {
        $date = new DateTime("first Monday of May $year");

        return $date->format('d/m');
    },
    'spring' => function ($year) {
        $date = new DateTime("last Monday of May $year");

        return $date->format('d/m');
    },
    'christmas' => function ($year) {
        $date = new DateTime("$year-12-25");

        if ($date->format('N') > 5) {
            $date->modify('+2 days');
        }

        return $date->format('d/m');
    },
    'boxing' => function ($year) {
        $date = new DateTime("$year-12-26");

        if ($date->format('N') > 5) {
            $date->modify('+2 days');
        }

        return $date->format('d/m');
    },
);
