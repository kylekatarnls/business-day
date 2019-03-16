<?php

return array_replace(require __DIR__.'/gb-national.php', array(
    '2nd-monday-of-march'     => '= second Monday of March',
    'easter'                  => '= easter',
    '04-28'                   => '04-28',
    '05-01'                   => '= 05-01 if Sunday, Saturday then next Monday',
    '1st-monday-of-may'       => '= first Monday of May',
    'monday-before-06-20'     => '= Monday before 06-20',
    'monday-before-september' => '= Monday before September',
    '09-10'                   => '09-10',
    '09-10-and'               => '= 09-10 if Sunday, Saturday then previous Monday',
));
