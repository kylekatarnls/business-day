<?php

namespace Cmixin\BusinessDay\Laravel;

use Closure;
use Cmixin\BusinessDay;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $config = $this->app->get('config')->get('carbon.holidays');

        if ($config instanceof Closure) {
            $config = $config($this->app);
        }

        if (is_array($config) && isset($config['region'])) {
            BusinessDay::enable(
                array_filter(array(
                    'Carbon\Carbon',
                    'Carbon\CarbonImmutable',
                    'Illuminate\Support\Carbon',
                    'Illuminate\Support\Facades\Date',
                ), 'class_exists'),
                $config['region'],
                isset($config['with']) ? $config['with'] : null
            );
        }
    }

    public function register()
    {
        // Needed for Laravel < 5.3 compatibility
    }
}
