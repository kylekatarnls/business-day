<?php

include __DIR__.'/vendor/autoload.php';
include __DIR__.'/src/Types/Generator.php';

$generator = new \Types\Generator();
$generator->writeHelpers('Cmixin\BusinessDay', __DIR__.'/src', __DIR__.'/types');
