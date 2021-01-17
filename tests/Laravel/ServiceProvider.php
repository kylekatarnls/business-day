<?php

namespace Illuminate\Support;

use App;

class ServiceProvider
{
    /**
     * @var App
     */
    public $app;

    public function __construct(...$arguments)
    {
        include_once __DIR__.'/App.php';
        $this->app = new App(...$arguments);
    }
}
