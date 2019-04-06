<?php

use Illuminate\Events\EventDispatcher;
use Symfony\Component\Translation\Translator;

class App
{
    /**
     * @var string
     */
    protected static $version;

    /**
     * @var Translator
     */
    public $translator;

    /**
     * @var EventDispatcher
     */
    public $events;

    public function get($name)
    {
        return $name === 'config' ? $this : array(
            'region' => 'us',
            'with'   => array(
                'foo' => '09-07',
            ),
        );
    }
}
