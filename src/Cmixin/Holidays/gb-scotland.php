<?php

return array(
    'new-year' => function ($year) {
        $date = new DateTime("$year-01-01");

        if ($date->format('N') > 5) {
            $date->modify('+2 days');
        }

        return $date->format('d/m');
    },
    'second-jan' => function ($year) {
        $date = new DateTime("$year-01-02");

        if ($date->format('N') > 5) {
            $date->modify('+2 days');
        }

        return $date->format('d/m');
    },
    'summer' => function ($year) {
        $date = new DateTime("first Monday of August $year");

        return $date->format('d/m');
    },
    'st-andrews' => function ($year) {
        $date = new DateTime("$year-11-30");

        if ($date->format('N') > 5) {
            $date->modify('next monday');
        }

        return $date->format('d/m');
    },
);
