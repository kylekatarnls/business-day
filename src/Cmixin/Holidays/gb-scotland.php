<?php

return array(
    'new-year'      => function ($year) {
        $date = DateTime::createFromFormat('U', (string)strtotime("$year/01/01"));

        if (date('N', strtotime("$year/01/01")) >= 6) {
            $date->add(new DateInterval('P2D'));
        }

        return $date->format('d/m');
    },
    'second-jan' => function ($year) {
        $date = DateTime::createFromFormat('U', (string)strtotime("$year/01/02"));

        if (date('N', strtotime("$year/01/02")) >= 6) {
            $date->add(new DateInterval('P2D'));
        }

        return $date->format('d/m');
    },
    'summer'   => function ($year) {
        $date = DateTime::createFromFormat('U', (string)strtotime("first Monday of August $year"));

        return $date->format('d/m');
    },
    'st-andrews' => function ($year) {
        $date = DateTime::createFromFormat('U', (string)strtotime("$year/11/30"));

        if (date('N', strtotime("$year/11/30")) == 6) {
            $date->add(new DateInterval('P2D'));
        } elseif (date('N', strtotime("$year/11/30")) == 7) {
            $date->add(new DateInterval('P1D'));
        }

        return $date->format('d/m');
    },
);
