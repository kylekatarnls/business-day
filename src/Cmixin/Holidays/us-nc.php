<?php

return array_replace(require __DIR__ . '/us-national.php', array (
  '3rd-monday-in-february' => '= third monday of February',
  '2nd-monday-in-october' => '= second monday of October',
  'easter-2' => '= easter -2',
  'friday-after-4th-thursday-in-november' => '= friday after fourth thursday of November',
  '12-24-and-if-friday-then-previous-thursday-if-saturday,sunday-then-previous-friday' => '= 12-24 and if friday then previous thursday if saturday,sunday then previous friday',
));
