<?php

return array_replace(require __DIR__ . '/nz-national.php', array (
  '12-01-if-tuesday,wednesday,thursday-then-previous-monday-if-friday,saturday,sunday-then-next-monday' => '= 12-01 if tuesday,wednesday,thursday then previous monday if friday,saturday,sunday then next monday',
));
