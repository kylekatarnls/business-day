<?php

return array_merge(include __DIR__.'/gb-national.php', array(
    'second-jan' => function ($year) {
        $date = new DateTime("$year-01-02");
        $day = (int) $date->format('N');

        if ($day === 6) {
            $date->modify('next monday');
        } elseif ($day === 7 || $day === 1) { // shifted by New Year
            $date->modify('next tuesday');
        }

        return $date->format('d/m');
    },
    'summer'     => '= first Monday of August',
    'st-andrews' => function ($year) {
        $date = new DateTime("$year-11-30");

        if ($date->format('N') > 5) {
            $date->modify('next monday');
        }

        return $date->format('d/m');
    },
));
