<?php

namespace Tests\Carbon\Laravel;

use BadMethodCallException;
use Carbon\Carbon;
use Cmixin\BusinessDay\Laravel\ServiceProvider;
use InvalidArgumentException;
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
        $this->assertFalse(Carbon::parse('2021-01-21')->isExtraWorkday());
        $this->assertSame($jan24Worked, Carbon::parse('2021-01-24')->isBusinessDay());
        $this->assertSame($jan24Worked, Carbon::parse('2021-01-24')->isExtraWorkday());

        $this->assertNull($service->register());
    }

    public static function getBootCases(): iterable
    {
        yield [[], false];
        yield [['without' => ['01-24']], true];
    }

    public function testExceptionOnNonStringRegion()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Region must be a string, integer provided.');

        include_once __DIR__.'/ServiceProvider.php';
        $service = new ServiceProvider();
        $service->app->region = 4;
        $service->boot();
    }
}
