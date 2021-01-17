<?php

class App
{
    public $region = 'us';

    /** @var array */
    protected $extraConfig;

    public function __construct(array $extraConfig = [])
    {
        $this->extraConfig = $extraConfig;
    }

    public function get($name)
    {
        return $name === 'config' ? $this : function ($app) {
            return array_merge([
                'region' => $app->region,
                'with'   => [
                    'foo' => '09-07',
                ],
            ], $this->extraConfig);
        };
    }
}
