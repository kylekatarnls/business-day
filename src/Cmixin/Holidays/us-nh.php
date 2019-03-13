<?php

return array_replace(require __DIR__.'/us-national.php', array(
    '2nd-monday-in-october'                              => '= second Monday of October',
    '3rd-monday-in-january'                              => '= third Monday of January',
    'tuesday-after-1st-monday-in-november-in-even-years' => '= Tuesday after first Monday of November of even years',
    'friday-after-4th-thursday-in-november'              => '= Friday after fourth Thursday of November',
));
