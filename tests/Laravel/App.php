<?php

class App
{
    public $region = 'us';

    public function get($name)
    {
        return $name === 'config' ? $this : function ($app) {
            return array(
                'region' => $app->region,
                'with'   => array(
                    'foo' => '09-07',
                ),
            );
        };
    }
}
