<?php

return array_merge(include __DIR__.'/gb-national.php', array(
    'st-patricks'   => function ($year) {
        $date = new DateTime("$year-03-17");

        if ($date->format('N') > 5) {
            $date->modify('next monday');
        }

        return $date->format('d/m');
    },
    'easter-monday' => '= easter + 1',
    'boyne'         => function ($year) {
        $date = new DateTime("$year-07-12");

        if ($date->format('N') > 5) {
            $date->modify('next monday');
        }

        return $date->format('d/m');
    },
    'summer'        => '= last Monday of August',
));
