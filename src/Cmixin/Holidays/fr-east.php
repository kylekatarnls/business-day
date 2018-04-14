<?php

return array_merge(include __DIR__.'/fr-national.php', array(
    function ($year) { // Vendredi Saint
        $days = easter_days($year) - 2;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    '26/12', // Saint Etienne
));
