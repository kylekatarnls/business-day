<?php

return array_merge(include __DIR__.'/fr-national.php', array(
    // Vendredi Saint
    'good-friday' => function ($year) {
        $days = easter_days($year) - 2;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    // Saint Etienne
    'christmas-next-day' => '26/12',
));
