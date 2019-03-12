<?php

return array_replace(require __DIR__.'/de-national.php', array(
  '12-31-14:00-if-sunday-then-00:00' => '= 12-31 14:00 if sunday then 00:00',
  '10-31'                            => '10-31',
));
