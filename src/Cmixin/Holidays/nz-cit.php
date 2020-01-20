<?php

return array_replace(require __DIR__.'/nz-national.php', [
    '11-30' => '= 11-30 if Tuesday,Wednesday,Thursday then previous Monday and if Friday,Saturday,Sunday then next Monday',
]);
