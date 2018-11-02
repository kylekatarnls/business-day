<?php

return array(
    'new-year' => '01/01', // New Year's Day
    'mlk-day' => function ($year) { // Martin Luther King Jr. Day
        $date = new DateTime("third monday of january $year");

        return $date->format('d/m');
    },
    'memorial-day' => function ($year) { // Memorial Day
        $date = new DateTime("last monday of may $year");

        return $date->format('d/m');
    },
    'independence-day' => '04/07', // Independence Day
    'labor-day' => function ($year) { // Labor Day
        $date = new DateTime("first monday of september $year");

        return $date->format('d/m');
    },
    'thanksgiving' => function ($year) { // Thanksgiving
        $date = new DateTime("fourth thursday of november $year");

        return $date->format('d/m');
    },
    'christmas' => '25/12', // Christmas Day
);
