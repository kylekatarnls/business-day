<?php

return array_replace(require __DIR__.'/ch-national.php', array(
  'christmas-next-day'  => '12-26',
  '12-26-not-on-monday' => '= 12-26 not on monday',
));
