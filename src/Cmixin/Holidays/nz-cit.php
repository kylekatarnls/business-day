<?php

return array_replace(require __DIR__.'/nz-national.php', array(
    '11-30-if-tuesday,wednesday,thursday-then-previous-monday-if-friday,saturday,sunday-then-next-monday' => '= 11-30 if Tuesday,Wednesday,Thursday then previous Monday if Friday,Saturday,Sunday then next Monday',
));
