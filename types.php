<?php

include __DIR__.'/vendor/autoload.php';
include __DIR__.'/src/Types/Generator.php';

$sources = __DIR__.'/src';
$generator = new \Types\Generator();
$generator->writeHelpers($sources, $sources);
