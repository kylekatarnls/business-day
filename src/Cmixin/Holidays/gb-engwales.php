<?php

return array_merge(include __DIR__.'/gb-national.php', array(
    'regions'       => array(
        'engwales' => 'engwales',
        'eng'      => 'engwales',
        'nireland' => 'nireland',
        'nir'      => 'nireland',
        'scotland' => 'scotland',
        'sct'      => 'scotland',
    ),
    'easter-monday' => '= easter + 1',
    'summer'        => '= last Monday of August',
));
