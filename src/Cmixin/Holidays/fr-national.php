<?php

return array(
    'new-year'      => '01/01', // Jour de l'an
    'epiphany'      => '06/01', // Épiphanie
    // Lundi de Pâques
    'easter-monday' => function ($year) {
        $days = easter_days($year) + 1;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    'labor-day'    => '01/05', // Fête du travail
    'victory-1945' => '08/05', // Victoire 1945
    // Ascension
    'ascension'   => function ($year) {
        $days = easter_days($year) + 39;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    // Lundi de Pentecôte
    'pentecost-monday' => function ($year) {
        $days = easter_days($year) + 50;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    'national-day'   => '14/07', // Fête nationale
    'assumption'     => '15/08', // Assomption
    'toussaint'      => '01/11', // Toussaint
    'armistice-1918' => '11/11', // Armistice 1918
    'christmas'      => '25/12', // Noël
);
