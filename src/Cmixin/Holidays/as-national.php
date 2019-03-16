<?php

return array_replace(require __DIR__.'/us-national.php', array(
    '04-15'                 => '= 04-15 if Friday then next Monday and if weekend then next Tuesday',
    '04-17'                 => '= 04-17 substitute',
    '2nd-sunday-of-october' => '= second Sunday of October',
));
