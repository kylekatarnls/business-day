<?php

namespace Tests\Carbon\Laravel;

use BadMethodCallException;
use Carbon\Carbon;
use Cmixin\BusinessDay\Laravel\ServiceProvider;
use PHPUnit\Framework\TestCase;

class ServiceProviderTest extends TestCase
{
    public function testBoot()
    {
        include_once __DIR__.'/ServiceProvider.php';
        $service = new ServiceProvider();
        $message = null;

        Carbon::macro('isHoliday', null);

        try {
            Carbon::parse('2019-04-08')->isHoliday();
        } catch (BadMethodCallException $e) {
            $message = $e->getMessage();
        }

        $this->assertSame('Method isHoliday does not exist.', $message);

        $service->boot();

        $this->assertSame('foo', Carbon::parse('2019-09-07')->getHolidayId());
        $this->assertSame('us-national', Carbon::getHolidaysRegion());

        $this->assertNull($service->register());
    }
}
