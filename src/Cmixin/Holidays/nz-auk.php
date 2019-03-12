<?php

return array_replace(require __DIR__ . '/nz-national.php', array (
  '01-29-if-tuesday,wednesday,thursday-then-previous-monday-if-friday,saturday,sunday-then-next-monday' => '= 01-29 if tuesday,wednesday,thursday then previous monday if friday,saturday,sunday then next monday',
));
