<?php

return array_replace(require __DIR__.'/nz-national.php', [
    '01-29' => '= 01-29 if Tuesday,Wednesday,Thursday then previous Monday and if Friday,Saturday,Sunday then next Monday',
]);
