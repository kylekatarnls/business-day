<?php

return array(
    '01/01', // Jour de l'an
    '06/01', // Épiphanie
    function ($year) { // Lundi de Pâques
        $days = easter_days($year) + 1;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    '01/05', // Fête du travail
    '08/05', // Victoire 1945
    function ($year) { // Ascension
        $days = easter_days($year) + 40;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    function ($year) { // Lundi de Pentecôte
        $days = easter_days($year) + 51;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    '14/07', // Fête nationale
    '15/08', // Assomption
    '01/11', // Toussaint
    '11/11', // Armistice 1918
    '25/12', // Noël
);
