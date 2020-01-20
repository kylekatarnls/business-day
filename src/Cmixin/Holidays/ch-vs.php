<?php

return array_replace(require __DIR__.'/ch-national.php', [
    'new-year-next-day'                 => null,
    '03-19'                             => '03-19',
    'easter-2'                          => '= easter -2',
    'easter-p1'                         => '= easter 1',
    '05-01-12:00'                       => '= 05-01 12:00',
    'easter-50'                         => '= easter 50',
    'easter-60'                         => '= easter 60',
    '08-15'                             => '08-15',
    'monday-after-3-sunday-after-09-01' => null,
    '11-01'                             => '11-01',
    '12-08'                             => '12-08',
    '12-24-12:00'                       => '= 12-24 12:00',
    'christmas-next-day'                => '12-26',
]);
