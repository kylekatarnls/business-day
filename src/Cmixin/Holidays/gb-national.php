<?php

return array(
    'new-year'    => function ($year) {
        $date = new DateTime("$year-01-01");

        if ($date->format('N') > 5) {
            $date->modify('next monday');
        }

        return $date->format('d/m');
    },
    'good-friday' => '= easter - 2',
    'early-may'   => '= first Monday of May',
    'spring'      => '= last Monday of May',
    'christmas'   => function ($year) {
        $date = new DateTime("$year-12-25");

        if ($date->format('N') > 5) {
            $date->modify('+2 days');
        }

        return $date->format('d/m');
    },
    'boxing'      => function ($year) {
        $date = new DateTime("$year-12-26");

        if ($date->format('N') > 5) {
            $date->modify('+2 days');
        }

        return $date->format('d/m');
    },
);
