<?php

return array_replace(require __DIR__.'/nz-national.php', array(
    '02-01' => '= 02-01 if Tuesday,Wednesday,Thursday then previous Monday and if Friday,Saturday,Sunday then next Monday',
));
