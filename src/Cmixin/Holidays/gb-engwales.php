<?php

return array_merge(include __DIR__.'/gb-national.php', [
    'easter-monday' => '= easter + 1',
    'summer'        => '= last Monday of August',
]);
