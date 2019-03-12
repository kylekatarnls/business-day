<?php

return array_replace(require __DIR__ . '/us-national.php', array (
  '2nd-monday-in-october' => '= second monday of October',
  '3rd-monday-in-january' => '= third monday of January',
  'tuesday-after-1st-monday-in-november-in-even-years' => '= tuesday after first monday of November of even years',
  'friday-after-4th-thursday-in-november' => '= friday after fourth thursday of November',
));
