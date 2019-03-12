<?php

return array_replace(require __DIR__.'/br-national.php', array(
  '08-11' => '08-11',
  '08-11-if-monday,tuesday,wednesday,thursday,friday,saturday-then-next-sunday' => '= 08-11 if monday,tuesday,wednesday,thursday,friday,saturday then next sunday',
  '11-25' => '11-25',
  '11-25-if-monday,tuesday,wednesday,thursday,friday,saturday-then-next-sunday' => '= 11-25 if monday,tuesday,wednesday,thursday,friday,saturday then next sunday',
));
