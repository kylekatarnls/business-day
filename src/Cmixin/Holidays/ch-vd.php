<?php

return array_replace(require __DIR__.'/ch-national.php', array(
    'new-year-next-day'                  => '01-02',
    'monday-after-3-sunday-in-september' => '= third Sunday of September $year + 1 day',
    'christmas-next-day'                 => '12-26',
));
