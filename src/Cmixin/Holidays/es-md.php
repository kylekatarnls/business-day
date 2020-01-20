<?php

return array_replace(require __DIR__.'/es-national.php', [
    '05-16' => '05-16',
    '11-09' => '= 11-09 if Sunday then next Monday',
]);
