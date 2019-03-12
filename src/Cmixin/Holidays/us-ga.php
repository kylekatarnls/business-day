<?php

return array_replace(require __DIR__ . '/us-national.php', array (
  '3rd-monday-in-february' => '= third monday of February',
  '01-19' => '01-19',
  'monday-before-05-01' => '= monday before 05-01',
  '12-24' => '12-24',
  'substitutes-12-24-if-wednesday-then-next-friday' => '= substitutes 12-24 if wednesday then next friday',
));
