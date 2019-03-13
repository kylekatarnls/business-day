<?php

return array_replace(require __DIR__.'/us-national.php', array(
    '3rd-monday-in-february'                                                             => '= third Monday of February',
    '2nd-monday-in-october'                                                              => '= second Monday of October',
    'easter-2'                                                                           => '= easter -2',
    'friday-after-4th-thursday-in-november'                                              => '= Friday after fourth Thursday of November',
    '12-24-and-if-friday-then-previous-thursday-if-saturday,sunday-then-previous-friday' => '= 12-24 and if Friday then previous Thursday if Saturday,sunday then previous Friday',
));
