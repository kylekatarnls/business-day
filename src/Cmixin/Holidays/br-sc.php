<?php

return array_replace(require __DIR__.'/br-national.php', array(
    '08-11' => '= 08-11 if not Sunday then next Sunday',
    '11-25' => '= 11-25 if not Sunday then next Sunday',
));
