<?php

return array(
    'new-year'           => '01/01', // Nieuwjaarsdag
    // Paaszondag
    'easter'             => '= easter',
    // Paasmaandag
    'easter-monday'      => '= easter + 1',
    // Koningsdag
    'royal-day'          => '= 04-27 if Sunday then -1 day',
    // Bevrijdingsdag
    'liberation-day'     => '= 05-05 every 5 years since 1945',
    // Hemelvaart
    'ascension'          => '= easter + 39',
    // Pinksterzondag
    'pentecost'          => '= easter + 49',
    // Pinkstermaandag
    'pentecost-monday'   => '= easter + 50',
    'christmas'          => '25/12', // Eerste Kerstdag
    'christmas-next-day' => '26/12', // Tweede Kerstdag
);
