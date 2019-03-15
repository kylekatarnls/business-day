<?php

return array_replace(require __DIR__.'/us-national.php', array(
    '3rd-monday-of-february'                => '= third Monday of February',
    '2nd-monday-of-october'                 => '= second Monday of October',
    'easter-2'                              => '= easter -2',
    'friday-after-4th-thursday-of-november' => '= Friday after fourth Thursday of November',
    '12-24-and'                             => '= 12-24 if Friday then previous Thursday and if weekend then previous Friday',
));
