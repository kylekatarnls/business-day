<?php

return array_replace(require __DIR__.'/ch-national.php', [
    'new-year-next-day'       => null,
    'jeune-genevois'          => '= first Sunday of September $year + 4 days',
    'christmas-next-day'      => null,
    'restauration-republique' => '12-31',
]);
