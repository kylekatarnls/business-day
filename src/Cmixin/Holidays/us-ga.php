<?php

return array_replace(require __DIR__.'/us-national.php', [
    '3rd-monday-of-february' => '= third Monday of February',
    '01-19'                  => '01-19',
    'monday-before-05-01'    => '= Monday before 05-01',
    '12-24'                  => '12-24',
    'substitutes-12-24'      => '= 12-24 if Wednesday then next Friday',
]);
