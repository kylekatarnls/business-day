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
            $classes = array_filter(array(
                'Carbon\Carbon',
                'Carbon\CarbonImmutable',
                'Illuminate\Support\Carbon',
            ), 'class_exists');

            // @codeCoverageIgnoreStart
            if (class_exists('Illuminate\Support\Facades\Date') &&
                (($now = \Illuminate\Support\Facades\Date::now()) instanceof \DateTimeInterface) &&
                !in_array($class = get_class($now), $classes)) {
                $classes[] = $class;
            }
            // @codeCoverageIgnoreEnd

            BusinessDay::enable($classes, $config['region'], isset($config['with']) ? $config['with'] : null);
        }
    }

    public function register()
    {
        // Needed for Laravel < 5.3 compatibility
    }
}
