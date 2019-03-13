<?php

return array_replace(require __DIR__.'/gb-national.php', array(
    '03-17'                                          => '03-17',
    'substitutes-03-17-if-saturday-then-next-monday' => '= substitutes 03-17 if Saturday then next Monday',
    'substitutes-03-17-if-sunday-then-next-monday'   => '= substitutes 03-17 if Sunday then next Monday',
    '07-12'                                          => '07-12',
    'substitutes-07-12-if-saturday-then-next-monday' => '= substitutes 07-12 if Saturday then next Monday',
    'substitutes-07-12-if-sunday-then-next-monday'   => '= substitutes 07-12 if Sunday then next Monday',
    '1st-monday-before-09-01'                        => '= last Monday of August',
));
