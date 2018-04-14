<?php

return array(
    '01/01', // New Year's Day
    function ($year) { // Martin Luther King Jr. Day
        $date = new DateTime("third monday of january $year");

        return $date->format('d/m');
    },
    function ($year) { // Memorial Day
        $date = new DateTime("last monday of may $year");

        return $date->format('d/m');
    },
    '04/07', // Independence Day
    function ($year) { // Labor Day
        $date = new DateTime("first monday of september $year");

        return $date->format('d/m');
    },
    function ($year) { // Thanksgiving
        $date = new DateTime("fourth thursday of november $year");

        return $date->format('d/m');
    },
    '25/12', // Christmas Day
);
