<?php

namespace Tests\Carbon\Laravel;

use BadMethodCallException;
use Carbon\Carbon;
use Cmixin\BusinessDay\Laravel\ServiceProvider;
use PHPUnit\Framework\TestCase;

class ServiceProviderTest extends TestCase
{
    /**
     * @dataProvider getBootCases
     */
    public function testBoot(array $appConfig, bool $jan24Worked): void
    {
        include_once __DIR__.'/ServiceProvider.php';
        $service = new ServiceProvider($appConfig);
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
        $this->assertSame($jan24Worked, Carbon::parse('2021-01-24')->isBusinessDay());

        $this->assertNull($service->register());
    }

    public function getBootCases(): iterable
    {
        yield [[], false];
        yield [['without' => ['01-24']], true];
    }
}
