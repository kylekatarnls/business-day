<?php

$replacements = [
    __DIR__ . '/../vendor/nesbot/carbon/src/Carbon/Traits/Creator.php' => [
        'private static function setLastErrors(array $' => 'private static function setLastErrors($',
    ],
];

foreach ($replacements as $file => $list) {
    $file = realpath($file);
    $contents = file_get_contents($file);
    $newContents = strtr($contents, $list);

    if ($contents === $newContents) {
        echo "$file already OK\n";

        continue;
    }

    if (!file_put_contents($file, $newContents)) {
        echo "$file cannot be updated\n";

        exit(1);
    }

    echo "$file updated\n";
}
