<?php

return array_replace(require __DIR__.'/ch-national.php', [
    'new-year-next-day'  => null,
    '01-06'              => '01-06',
    '03-19'              => '03-19',
    'easter-2'           => '= easter -2',
    'easter-60'          => '= easter 60',
    '05-01'              => '05-01',
    '06-29'              => '06-29',
    '1st-sunday-in-june' => '= first Sunday of June',
    '08-15'              => '08-15',
    '11-01'              => '11-01',
    '12-08'              => '12-08',
]);
