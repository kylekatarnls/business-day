<?php

return array_replace(require __DIR__.'/br-national.php', array(
    '08-11' => '08-11',
    '08-11' => '= 08-11 if Monday,Tuesday,Wednesday,Thursday,Friday,Saturday then next Sunday',
    '11-25' => '11-25',
    '11-25' => '= 11-25 if Monday,Tuesday,Wednesday,Thursday,Friday,Saturday then next Sunday',
));
