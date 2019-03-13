<?php

return array_replace(require __DIR__.'/gb-national.php', array(
    'substitutes-01-01-if-sunday-then-next-monday'    => '= substitutes 01-01 if Sunday then next Monday',
    'substitutes-01-01-if-saturday-then-next-monday'  => '= substitutes 01-01 if Saturday then next Monday',
    'substitutes-01-01-if-saturday-then-next-tuesday' => '= substitutes 01-01 if Saturday then next Tuesday',
    'substitutes-01-01-if-sunday-then-next-tuesday'   => '= substitutes 01-01 if Sunday then next Tuesday',
    'new-year-next-day'                               => '01-02',
    'substitutes-01-02-if-saturday-then-next-monday'  => '= substitutes 01-02 if Saturday then next Monday',
    'substitutes-01-02-if-sunday-then-next-monday'    => '= substitutes 01-02 if Sunday then next Monday',
    'easter-1'                                        => '= easter 1',
    '1st-monday-in-august'                            => '= first Monday of August',
    '11-30'                                           => '11-30',
));
