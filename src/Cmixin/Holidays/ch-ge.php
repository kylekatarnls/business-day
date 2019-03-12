<?php

return array_replace(require __DIR__.'/ch-national.php', array(
    'new-year-next-day'  => null,
    'jeune-genevois'     => '= first Sunday of September $year + 4 days',
    'christmas-next-day' => null,
    '12-31'              => '12-31',
));
