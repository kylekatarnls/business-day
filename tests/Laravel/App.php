<?php

class App
{
    public $region = 'us';

    public function get($name)
    {
        return $name === 'config' ? $this : function ($app) {
            return [
                'region' => $app->region,
                'with'   => [
                    'foo' => '09-07',
                ],
            ];
        };
    }
}
