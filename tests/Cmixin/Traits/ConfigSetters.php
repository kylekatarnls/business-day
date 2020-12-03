<?php

namespace Tests\Cmixin\Traits;

use Carbon\CarbonInterface;
use Cmixin\BusinessDay;

trait ConfigSetters
{
    public function testHolidayGetter()
    {
        $apiMock = [
            '2020' => [
                'us' => [
                    'il' => [
                        '2020-02-03' => [
                            'id'   => 'foo',
                            'name' => 'February Foo',
                            'info' => 'First one',
                        ],
                        '2020-11-23' => [
                            'id'   => 'bar',
                            'name' => 'November Bar',
                            'info' => 'Last one',
                        ],
                    ],
                ],
            ],
        ];
        $carbon = static::CARBON_CLASS;
        BusinessDay::enable($carbon, 'us-IL');
        $carbon::setHolidayGetter(function (string $region, CarbonInterface $self, callable $fallback) use ($apiMock, $carbon) {
            [$country, $state] = explode('-', $region);
            $yearHolidays = $apiMock[$self->year] ?? null;

            if (!$yearHolidays) {
                // Optionally the default method (using internal lists) can be used as a fallback:
                return $fallback();
            }

            $holiday = (($yearHolidays[$country] ?? [])[$state] ?? [])[$self->format('Y-m-d')] ?? null;

            if (!$holiday) {
                return false;
            }

            $carbon::setHolidayName($holiday['id'], 'en', $holiday['name']);
            $carbon::setHolidayDataById($holiday['id'], $holiday);

            return $holiday['id'];
        });

        self::assertFalse($carbon::parse('2020-01-01')->isHoliday()); // 2020 uses the custom getter
        self::assertTrue($carbon::parse('2021-01-01')->isHoliday()); // 2021 uses the fallback
        self::assertTrue($carbon::parse('2020-02-03')->isHoliday());
        self::assertSame('February Foo', $carbon::parse('2020-02-03')->getHolidayName());
        self::assertSame('bar', $carbon::parse('2020-11-23')->getHolidayId());
        self::assertSame('Last one', $carbon::parse('2020-11-23')->getHolidayData()['info']);
        self::assertSame('First one', $carbon::parse('2020-11-23')->setHolidayGetter(static function () {
            return 'foo';
        })->getHolidayData()['info']);
        self::assertSame('Last one', $carbon::parse('2020-11-23')->getHolidayData()['info']);
    }

    public function testSetBusinessDayChecker()
    {
        $carbon = static::CARBON_CLASS;
        BusinessDay::enable($carbon, 'us-IL');

        self::assertSame('2020-12-07', $carbon::parse('2020-12-03')->addBusinessDays(2)->format('Y-m-d'));
        self::assertSame(
            '2020-12-05',
            $carbon::parse('2020-12-03')->setBusinessDayChecker(static function (CarbonInterface $date) {
                return !$date->isSunday() && !$date->isHoliday();
            })->addBusinessDays(2)->format('Y-m-d')
        );
        self::assertSame('2020-12-07', $carbon::parse('2020-12-03')->addBusinessDays(2)->format('Y-m-d'));
        $carbon::setBusinessDayChecker(static function (CarbonInterface $date) {
            return !$date->isSunday() && !$date->isHoliday();
        });
        self::assertSame('2020-12-05', $carbon::parse('2020-12-03')->addBusinessDays(2)->format('Y-m-d'));
        $carbon::setBusinessDayChecker(null);
        self::assertSame('2020-12-07', $carbon::parse('2020-12-03')->addBusinessDays(2)->format('Y-m-d'));
    }
}
