<?php

namespace Tests\Cmixin;

class IlluminateCarbonTest extends BusinessDayTest
{
    const CARBON_CLASS = 'Tests\Cmixin\IlluminateCarbon';

    protected function setUp()
    {
        if (version_compare(phpversion(), '5.5.0-dev', '<')) {
            self::markTestSkipped('IlluminateCarbon compatible only from PHP 5.5');
        }

        parent::setUp();
    }
}
