<?php

return array(
    'new-year' => '01/01', // New Year's Day
    // Martin Luther King Jr. Day
    'mlk-day'  => function ($year) {
        $date = new DateTime("third monday of january $year");

        return $date->format('d/m');
    },
    // Memorial Day
    'memorial-day' => function ($year) {
        $date = new DateTime("last monday of may $year");

        return $date->format('d/m');
    },
    'independence-day' => '04/07', // Independence Day
    // Labor Day
    'labor-day'        => function ($year) {
        $date = new DateTime("first monday of september $year");

        return $date->format('d/m');
    },
    // Thanksgiving
    'thanksgiving' => function ($year) {
        $date = new DateTime("fourth thursday of november $year");

        return $date->format('d/m');
    },
    'christmas' => '25/12', // Christmas Day
);
