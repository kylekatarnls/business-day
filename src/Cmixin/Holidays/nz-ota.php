<?php

return array_replace(require __DIR__.'/nz-national.php', array(
    '03-23-if-tuesday,wednesday,thursday-then-previous-monday-if-friday,saturday,sunday-then-next-monday' => '= 03-23 if Tuesday,wednesday,thursday then previous Monday if Friday,saturday,sunday then next Monday',
));
