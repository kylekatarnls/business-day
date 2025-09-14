<?php

namespace Tests\Cmixin;

use BadMethodCallException;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Cmixin\BusinessDay;
use Cmixin\BusinessDay\Calculator\HolidayCalculator;
use Cmixin\BusinessDay\Calculator\MixinConfigPropagator;
use DateTime;
use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Cmixin\Traits\AddAndSubDays;
use Tests\Cmixin\Traits\AlternativeCalendars;
use Tests\Cmixin\Traits\ConfigSetters;
use Tests\Cmixin\Traits\Observable;
use Tests\Cmixin\Traits\PreviousAndNext;

/**
 * @group mutable
 */
class BusinessDayTest extends TestCase
{
    use AddAndSubDays;
    use AlternativeCalendars;
    use ConfigSetters;
    use Observable;
    use PreviousAndNext;

    const CARBON_CLASS = 'Carbon\Carbon';

    public static function assertCarbonList($expected, $actual, $format = 'Y-m-d')
    {
        $output = [];

        foreach ($actual as $key => $date) {
            self::assertInstanceOf(static::CARBON_CLASS, $date);
            $output[$key] = $date->format($format);
        }

        self::assertSame($expected, $output);
    }

    protected function setUp(): void
    {
        BusinessDay::enable(static::CARBON_CLASS);
        $carbon = static::CARBON_CLASS;
        $carbon::resetHolidays();
    }

    public function testEnable()
    {
        $carbon = static::CARBON_CLASS;
        self::assertTrue($carbon::enable());
    }

    public function testMutability()
    {
        $carbon = static::CARBON_CLASS;

        $date = $carbon::parse('2018-04-16 12:00:00');
        self::assertSame($date, $date->currentOrNextBusinessDay());

        $date = $carbon::parse('2018-11-11 12:00:00');
        self::assertSame($date, $date->currentOrNextBusinessDay());
        self::assertSame('12/11/2018', $date->format('d/m/Y'));

        $date = $carbon::parse('2018-04-16 12:00:00');
        self::assertSame($date, $date->previousBusinessDay());
        self::assertSame('13/04/2018', $date->format('d/m/Y'));

        $date = $carbon::parse('2018-11-11 12:00:00');
        self::assertSame($date, $date->previousBusinessDay());
        self::assertSame('09/11/2018', $date->format('d/m/Y'));

        $date = $carbon::parse('2018-04-16 12:00:00');
        self::assertSame($date, $date->nextBusinessDay());
        self::assertSame('17/04/2018', $date->format('d/m/Y'));

        $date = $carbon::parse('2018-11-11 12:00:00');
        self::assertSame($date, $date->nextBusinessDay());
        self::assertSame('12/11/2018', $date->format('d/m/Y'));

        $date = $carbon::parse('2018-04-16 12:00:00');
        self::assertSame($date, $date->currentOrPreviousBusinessDay());
        self::assertSame('16/04/2018', $date->format('d/m/Y'));

        $date = $carbon::parse('2018-11-11 12:00:00');
        self::assertSame($date, $date->currentOrPreviousBusinessDay());
        self::assertSame('09/11/2018', $date->format('d/m/Y'));

        $date = $carbon::parse('2018-11-11 12:00:00');
        self::assertSame($date, $date->addBusinessDays(17));
        self::assertSame('04/12/2018', $date->format('d/m/Y'));

        $carbon::setHolidaysRegion('fr-national');

        $date = $carbon::parse('2018-11-11 12:00:00');
        self::assertSame($date, $date->subBusinessDays(17));
        self::assertSame('17/10/2018', $date->format('d/m/Y'));

        $date = $carbon::parse('2018-11-11 12:00:00');
        self::assertSame($date, $date->subtractBusinessDays(17));
        self::assertSame('17/10/2018', $date->format('d/m/Y'));
    }

    public function testUsHolidays()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('us-national');
        self::assertSame('us-national', $carbon::getHolidaysRegion());
        self::assertTrue($carbon::parse('2018-01-01 00:00:00')->isHoliday());
        self::assertTrue($carbon::parse('2018-01-15 00:00:00')->isHoliday());
        self::assertTrue($carbon::parse('2018-05-28 00:00:00')->isHoliday());
        self::assertTrue($carbon::parse('2018-07-04 00:00:00')->isHoliday());
        self::assertTrue($carbon::parse('2018-09-03 00:00:00')->isHoliday());
        self::assertTrue($carbon::parse('2018-11-22 00:00:00')->isHoliday());
        self::assertTrue($carbon::parse('2018-12-25 00:00:00')->isHoliday());
    }

    public function testIsHoliday()
    {
        $carbon = static::CARBON_CLASS;
        $coruscantHolidays = [
            '27/12',
            '28/12',
        ];
        for ($year = 1808; $year < 2500; $year += 20) {
            $carbon::resetHolidays();
            self::assertSame([], $carbon::getHolidays());
            $carbon::setHolidays('coruscant', $coruscantHolidays);
            self::assertFalse($carbon::parse("$year-12-25 03:30:40")->isHoliday());
            self::assertFalse($carbon::parse("$year-12-26 03:30:40")->isHoliday());
            self::assertFalse($carbon::parse("$year-12-27 03:30:40")->isHoliday());
            $carbon::setHolidaysRegion('us-national');
            $christmas = $carbon::parse("$year-12-25 03:30:40");
            while ((int) $christmas->format('N') > 5) {
                $christmas = $christmas->addDay();
            }
            self::assertTrue($christmas->isHoliday());
            $christmas = $christmas->addDay();
            self::assertFalse($christmas->isHoliday());
            $christmas = $christmas->addDay();
            self::assertFalse($christmas->isHoliday());
            $carbon::setHolidaysRegion('fr-east');
            self::assertTrue($carbon::parse("$year-12-25 03:30:40")->isHoliday());
            self::assertTrue($carbon::parse("$year-12-26 03:30:40")->isHoliday());
            self::assertFalse($carbon::parse("$year-12-27 03:30:40")->isHoliday());
            $carbon::setHolidaysRegion('fr-national');
            self::assertTrue($carbon::parse("$year-12-25 03:30:40")->isHoliday());
            self::assertFalse($carbon::parse("$year-12-26 03:30:40")->isHoliday());
            self::assertFalse($carbon::parse("$year-12-27 03:30:40")->isHoliday());
            $carbon::addHolidays('fr-national', [
                '15/11',
                '27/12',
            ]);
            self::assertTrue($carbon::parse("$year-12-25 03:30:40")->isHoliday());
            self::assertFalse($carbon::parse("$year-12-26 03:30:40")->isHoliday());
            self::assertTrue($carbon::parse("$year-12-27 03:30:40")->isHoliday());
            $carbon::setHolidaysRegion('coruscant');
            self::assertFalse($carbon::parse("$year-12-25 03:30:40")->isHoliday());
            self::assertFalse($carbon::parse("$year-12-26 03:30:40")->isHoliday());
            self::assertTrue($carbon::parse("$year-12-27 03:30:40")->isHoliday());
            self::assertSame($coruscantHolidays, $carbon::getHolidays());

            self::assertTrue($carbon::initializeHolidaysRegion());
        }
    }

    public function testWeekendRule()
    {
        $carbon = static::CARBON_CLASS;
        $coruscantHolidays = [
            '= 03-01 if not weekend then next Saturday',
        ];
        $carbon::resetHolidays();
        $carbon::setHolidays('coruscant', $coruscantHolidays);
        $carbon::setHolidaysRegion('coruscant');
        self::assertFalse($carbon::parse('2018-03-01 03:30:40')->isHoliday());
        self::assertFalse($carbon::parse('2018-03-02 03:30:40')->isHoliday());
        self::assertTrue($carbon::parse('2018-03-03 03:30:40')->isHoliday());
        self::assertFalse($carbon::parse('2019-03-01 03:30:40')->isHoliday());
        self::assertTrue($carbon::parse('2019-03-02 03:30:40')->isHoliday());
        self::assertFalse($carbon::parse('2019-03-03 03:30:40')->isHoliday());
        self::assertTrue($carbon::parse('2020-03-01 03:30:40')->isHoliday());
        self::assertFalse($carbon::parse('2020-03-02 03:30:40')->isHoliday());
        self::assertFalse($carbon::parse('2020-03-03 03:30:40')->isHoliday());
    }

    public function testMultipleIfRule()
    {
        $carbon = static::CARBON_CLASS;
        $guemesHolidays = [
            'guemes-day' => '= 06-17 if Tuesday,Wednesday then previous Monday and if Thursday,Friday then next Monday',
        ];
        $carbon::resetHolidays();
        $carbon::setHolidays('guemes', $guemesHolidays);
        $carbon::setHolidaysRegion('guemes');

        self::assertFalse($carbon::parse('2021-06-17')->isHoliday());
        self::assertFalse($carbon::parse('2021-06-14')->isHoliday());
        self::assertTrue($carbon::parse('2021-06-21')->isHoliday());

        self::assertFalse($carbon::parse('2022-06-17')->isHoliday());
        self::assertFalse($carbon::parse('2022-06-13')->isHoliday());
        self::assertTrue($carbon::parse('2022-06-20')->isHoliday());

        self::assertTrue($carbon::parse('2023-06-17')->isHoliday());
        self::assertFalse($carbon::parse('2023-06-12')->isHoliday());
        self::assertFalse($carbon::parse('2023-06-19')->isHoliday());

        self::assertFalse($carbon::parse('2025-06-17')->isHoliday());
        self::assertTrue($carbon::parse('2025-06-16')->isHoliday());
        self::assertFalse($carbon::parse('2025-06-23')->isHoliday());
    }

    public function testIfRuleWithHijriCalendar()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::resetHolidays();
        $carbon::setHolidays('custom', [
            'hari-raya-puasa' => '= 1 Shawwal if sunday then next monday',
        ]);
        $carbon::setHolidaysRegion('custom');
        $year = static function (int $year) use ($carbon) {
            return array_map('strval', $carbon::getYearHolidays($year));
        };

        self::assertSame(['hari-raya-puasa' => '2020-05-25 00:00:00'], $year(2020));
        self::assertSame(['hari-raya-puasa' => '2021-05-13 00:00:00'], $year(2021));

        $carbon::resetHolidays();
        $carbon::setHolidays('custom', [
            'hari-raya-haji' => '= 10 dhu al-hijjah if sunday then next monday',
        ]);
        $carbon::setHolidaysRegion('custom');
        $year = static function (int $year) use ($carbon) {
            return array_map('strval', $carbon::getYearHolidays($year));
        };

        self::assertSame(['hari-raya-haji' => '2000-03-16 00:00:00'], $year(2000));
        self::assertSame(['hari-raya-haji' => '2001-03-06 00:00:00'], $year(2001));
        self::assertSame(['hari-raya-haji' => '2002-02-23 00:00:00'], $year(2002));
        self::assertSame(['hari-raya-haji' => '2003-02-12 00:00:00'], $year(2003));
        self::assertSame(['hari-raya-haji' => '2004-02-02 00:00:00'], $year(2004));
        self::assertSame(['hari-raya-haji' => '2005-01-21 00:00:00'], $year(2005));
        self::assertSame([
            'hari-raya-haji'      => '2006-01-10 00:00:00',
            'hari-raya-haji-oc-2' => '2006-12-31 00:00:00',
        ], $year(2006));
        self::assertSame(['hari-raya-haji' => '2009-11-28 00:00:00'], $year(2009));
        self::assertSame(['hari-raya-haji' => '2010-11-17 00:00:00'], $year(2010));
        self::assertSame(['hari-raya-haji' => '2011-11-07 00:00:00'], $year(2011));
        self::assertSame(['hari-raya-haji' => '2021-07-20 00:00:00'], $year(2021));
        self::assertSame(['hari-raya-haji' => '2022-07-11 00:00:00'], $year(2022));
        self::assertSame(['hari-raya-haji' => '2023-06-29 00:00:00'], $year(2023));
        self::assertSame(['hari-raya-haji' => '2024-06-17 00:00:00'], $year(2024));
    }

    public function testCaseInsensitivity()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('FR');
        self::assertTrue($carbon::parse('2015-01-01 03:30:40')->isHoliday());
        self::assertTrue($carbon::parse('2015-07-14 03:30:40')->isHoliday());
        self::assertTrue($carbon::parse('2015-12-25 03:30:40')->isHoliday());
        self::assertFalse($carbon::parse('2015-12-26 03:30:40')->isHoliday());
    }

    public function testRegionsAlias()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('fr_68');
        self::assertTrue($carbon::parse('2015-01-01 03:30:40')->isHoliday());
        self::assertTrue($carbon::parse('2015-07-14 03:30:40')->isHoliday());
        self::assertTrue($carbon::parse('2015-12-25 03:30:40')->isHoliday());
        self::assertTrue($carbon::parse('2015-12-26 03:30:40')->isHoliday());
        $carbon::setHolidaysRegion('fr_75');
        self::assertTrue($carbon::parse('2015-01-01 03:30:40')->isHoliday());
        self::assertTrue($carbon::parse('2015-07-14 03:30:40')->isHoliday());
        self::assertTrue($carbon::parse('2015-12-25 03:30:40')->isHoliday());
        self::assertFalse($carbon::parse('2015-12-26 03:30:40')->isHoliday());
    }

    public function testAddHolidaysArrayNotDate()
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('Holiday array definition should at least contains a "date" entry.');

        $carbon = static::CARBON_CLASS;
        $carbon::addHolidays('fr-national', [
            'foo-bar' => [
                'observe' => true,
            ],
        ]);
    }

    public function testAddHolidaysInEnable()
    {
        $carbon = static::CARBON_CLASS;
        BusinessDay::enable($carbon, 'fr-national', [
            'custom-holiday' => '05/12',
            'christmas'      => null,
        ]);
        self::assertTrue($carbon::parse('2010-01-01')->isHoliday());
        self::assertTrue($carbon::parse('2010-12-05')->isHoliday());
        self::assertFalse($carbon::parse('2010-12-25')->isHoliday());
    }

    public function testAddHolidaysArrayIntKey()
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('Holiday array definition need a string identifier as main array key.');

        $carbon = static::CARBON_CLASS;
        $carbon::addHolidays('fr-national', [
            [
                'date'    => '15/11',
                'observe' => true,
            ],
        ]);
    }

    public function testAddHolidaysArray()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::addHolidays('fr-national', [
            'foo-bar' => [
                'date'     => '15/11',
                'observed' => true,
                'name'     => [
                    'en' => 'Foo bar',
                    'fr' => 'Machin chose',
                ],
            ],
        ]);
        self::assertFalse($carbon::parse('2010-11-15 03:30:40')->isHoliday());
        self::assertFalse($carbon::parse('2010-11-15 03:30:40')->isObservedHoliday());
        self::assertFalse($carbon::parse('2010-11-15 03:30:40')->getHolidayName());
        $carbon::setHolidaysRegion('fr-national');
        self::assertTrue($carbon::parse('2010-11-15 03:30:40')->isHoliday());
        self::assertTrue($carbon::parse('2010-11-15 03:30:40')->isObservedHoliday());
        self::assertSame('Foo bar', $carbon::parse('2010-11-15 03:30:40')->getHolidayName());
        self::assertSame('Machin chose', $carbon::parse('2010-11-15 03:30:40')->getHolidayName('fr'));
        self::assertSame('Unknown', $carbon::parse('2010-11-15 03:30:40')->getHolidayName('nl'));
    }

    public function testSetHolidayName()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('fr-national');
        self::assertSame('Christmas', $carbon::parse('2018-12-25')->getHolidayName());
        $carbon::setHolidayName('christmas', 'en', 'Christmas Day');
        self::assertSame('Christmas Day', $carbon::parse('2018-12-25')->getHolidayName());
        self::assertSame('Noël', $carbon::parse('2018-12-25')->getHolidayName('fr'));
    }

    public function testIsHolidayStatic()
    {
        $carbon = static::CARBON_CLASS;
        $coruscantHolidays = [
            '12-27',
            '28/12',
        ];
        for ($year = 1808; $year < 2500; $year += 20) {
            $carbon::resetHolidays();
            $carbon::setHolidays('coruscant', $coruscantHolidays);
            self::assertSame($coruscantHolidays, $carbon::getHolidays('coruscant'));
            self::assertSame([], $carbon::getHolidays());
            $carbon::setTestNow($carbon::parse("$year-12-25 03:30:40"));
            self::assertFalse($carbon::isHoliday());
            $carbon::setTestNow($carbon::parse("$year-12-26 03:30:40"));
            self::assertFalse($carbon::isHoliday());
            $carbon::setTestNow($carbon::parse("$year-12-27 03:30:40"));
            self::assertFalse($carbon::isHoliday());
            $carbon::setHolidaysRegion('us-national');
            $christmas = $carbon::parse("$year-12-25 03:30:40");
            while ((int) $christmas->format('N') > 5) {
                $christmas = $christmas->addDay();
            }
            self::assertTrue($christmas->isHoliday());
            $christmas = $christmas->addDay();
            self::assertFalse($christmas->isHoliday());
            $christmas = $christmas->addDay();
            self::assertFalse($christmas->isHoliday());
            $carbon::setHolidaysRegion('fr-east');
            $carbon::setTestNow($carbon::parse("$year-12-25 03:30:40"));
            self::assertTrue($carbon::isHoliday());
            $carbon::setTestNow($carbon::parse("$year-12-26 03:30:40"));
            self::assertTrue($carbon::isHoliday());
            $carbon::setTestNow($carbon::parse("$year-12-27 03:30:40"));
            self::assertFalse($carbon::isHoliday());
            $carbon::setHolidaysRegion('fr-national');
            $carbon::setTestNow($carbon::parse("$year-12-25 03:30:40"));
            self::assertTrue($carbon::isHoliday());
            $carbon::setTestNow($carbon::parse("$year-12-26 03:30:40"));
            self::assertFalse($carbon::isHoliday());
            $carbon::setTestNow($carbon::parse("$year-12-27 03:30:40"));
            self::assertFalse($carbon::isHoliday());
            $carbon::addHolidays('fr-national', [
                '15/11',
                '27/12',
            ]);
            $carbon::setTestNow($carbon::parse("$year-12-25 03:30:40"));
            self::assertTrue($carbon::isHoliday());
            $carbon::setTestNow($carbon::parse("$year-12-26 03:30:40"));
            self::assertFalse($carbon::isHoliday());
            $carbon::setTestNow($carbon::parse("$year-12-27 03:30:40"));
            self::assertTrue($carbon::isHoliday());
            $carbon::setHolidaysRegion('coruscant');
            $carbon::setTestNow($carbon::parse("$year-12-25 03:30:40"));
            self::assertFalse($carbon::isHoliday());
            $carbon::setTestNow($carbon::parse("$year-12-26 03:30:40"));
            self::assertFalse($carbon::isHoliday());
            $carbon::setTestNow($carbon::parse("$year-12-27 03:30:40"));
            self::assertTrue($carbon::isHoliday());
            self::assertSame($coruscantHolidays, $carbon::getHolidays());
        }
    }

    public function testYearSpecificHoliday()
    {
        $carbon = static::CARBON_CLASS;
        $specialHolidays = [
            '2003-01-03',
            '04/01/2004',
        ];
        $carbon::resetHolidays();
        $carbon::setHolidays('special', $specialHolidays);
        $carbon::setHolidaysRegion('special');
        $carbon::setTestNow($carbon::parse('2002-01-03 03:30:40'));
        self::assertFalse($carbon::isHoliday());
        $carbon::setTestNow($carbon::parse('2003-01-03 03:30:40'));
        self::assertTrue($carbon::isHoliday());
        $carbon::setTestNow($carbon::parse('2004-01-03 03:30:40'));
        self::assertFalse($carbon::isHoliday());
        $carbon::setTestNow($carbon::parse('2002-01-04 03:30:40'));
        self::assertFalse($carbon::isHoliday());
        $carbon::setTestNow($carbon::parse('2003-01-04 03:30:40'));
        self::assertFalse($carbon::isHoliday());
        $carbon::setTestNow($carbon::parse('2004-01-04 03:30:40'));
        self::assertTrue($carbon::isHoliday());
    }

    public function testAddHolidaysTraversable()
    {
        if (version_compare(phpversion(), '5.5.0-dev', '<')) {
            self::markTestSkipped('Generators not available before PHP 5.5');
        }

        $carbon = static::CARBON_CLASS;
        $generators = new TestGenerators();
        $generators->run($carbon);
        $carbon::setHolidaysRegion('test');
        self::assertTrue($carbon::parse('2018-02-08 03:30:40')->isHoliday());
        self::assertFalse($carbon::parse('2018-01-08 03:30:40')->isHoliday());
        self::assertFalse($carbon::parse('2016-02-08 03:30:40')->isHoliday());
        self::assertTrue($carbon::parse('2023-03-03 03:30:40')->isHoliday());
        self::assertFalse($carbon::parse('2023-05-03 03:30:40')->isHoliday());
        self::assertTrue($carbon::parse('2023-06-03 03:30:40')->isHoliday());
    }

    public function testBadRegion()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::resetHolidays();
        $carbon::setHolidaysRegion('i-do-not-exist');
        self::assertFalse($carbon::parse('2018-01-01 12:00:00')->isHoliday());
    }

    public function testIsBusinessDay()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('fr-national');
        self::assertFalse($carbon::parse('2018-05-01 12:00:00')->isBusinessDay());
        self::assertTrue($carbon::parse('2018-04-04 12:00:00')->isBusinessDay());
        self::assertFalse($carbon::parse('2018-04-14 12:00:00')->isBusinessDay());
        self::assertFalse($carbon::parse('2018-04-15 12:00:00')->isBusinessDay());
        self::assertTrue($carbon::parse('2018-04-16 12:00:00')->isBusinessDay());
        self::assertFalse($carbon::parse('2018-11-11 12:00:00')->isBusinessDay());
    }

    public function testIsBusinessDayStatic()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('fr-national');
        $carbon::setTestNow($carbon::parse('2018-05-01 12:00:00'));
        self::assertFalse($carbon::isBusinessDay());
        $carbon::setTestNow($carbon::parse('2018-04-04 12:00:00'));
        self::assertTrue($carbon::isBusinessDay());
        $carbon::setTestNow($carbon::parse('2018-04-14 12:00:00'));
        self::assertFalse($carbon::isBusinessDay());
        $carbon::setTestNow($carbon::parse('2018-04-15 12:00:00'));
        self::assertFalse($carbon::isBusinessDay());
        $carbon::setTestNow($carbon::parse('2018-04-16 12:00:00'));
        self::assertTrue($carbon::isBusinessDay());
        $carbon::setTestNow($carbon::parse('2018-11-11 12:00:00'));
        self::assertFalse($carbon::isBusinessDay());
    }

    public function testGetHolidayId()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('fr-national');
        $date = $carbon::parse('2018-12-26 12:00:00');
        self::assertFalse($date->getHolidayId());
        $carbon::setHolidaysRegion('fr-east');
        self::assertSame('christmas-next-day', $date->getHolidayId());
        $carbon::setHolidaysRegion('si-national');
        self::assertSame('independence-day', $date->getHolidayId());
    }

    public function testGetHolidayName()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setLocale('en');
        $carbon::setHolidaysRegion('fr-national');
        $carbon::setTestNow('2018-12-25');
        self::assertSame('en', $carbon::getLocale());
        self::assertSame('Christmas', $carbon::getHolidayName());
        self::assertSame('National Day', $carbon::getHolidayName(new DateTime('2018-07-14')));
        self::assertSame('Noël', $carbon::getHolidayName('fr'));
        $carbon::setTestNow('2018-12-26');
        self::assertFalse($carbon::getHolidayName());
        self::assertSame('New Year', $carbon::parse('2018-01-01')->getHolidayName());
        self::assertSame('Novo leto', $carbon::parse('2018-01-01')->getHolidayName('sl_SI'));
        $carbon::setLocale('nl');
        self::assertSame('nl', $carbon::getLocale());
        self::assertSame('Nieuwjaarsdag', $carbon::parse('2018-01-01')->getHolidayName());
        $carbon::setLocale('de'); // Language not translated
        self::assertSame('de', $carbon::getLocale());
        self::assertSame('Neujahr', $carbon::parse('2018-01-01')->getHolidayName());
    }

    public function testGetHolidayNameLocalLocale()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setLocale('en');
        $carbon::setHolidaysRegion('fr-national');
        $date = $carbon::parse('2018-01-01');

        if (!method_exists($date, 'locale')) {
            self::markTestSkipped('Test for Carbon 2 only.');
        }

        self::assertSame('New Year', $date->getHolidayName());
        self::assertSame('Nouvel an', $date->locale('fr')->getHolidayName());
    }

    public function testObserveHolidaysInvalidArgument()
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('You must pass holiday names as a string or "all".');

        $carbon = static::CARBON_CLASS;
        $carbon::observeHoliday(42);
    }

    public function testDiffInBusinessDays()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('fr-national');
        $carbon::setTestNow('2020-07-16');

        self::assertSame(0, $carbon::diffInBusinessDays());
        self::assertSame(4, abs($carbon::diffInBusinessDays('2020-07-09')));
        self::assertSame(3, $carbon::parse('2020-07-06')->diffInBusinessDays('2020-07-09'));
        self::assertSame(24, $carbon::parse('2020-07-06')->diffInBusinessDays('2020-08-09'));
    }

    public function testGetBusinessDaysInMonth()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('fr-national');
        $carbon::setTestNow('2020-07-16');

        self::assertSame(22, $carbon::getBusinessDaysInMonth());
        self::assertSame(23, $carbon::getBusinessDaysInMonth('2019-07'));
        self::assertSame(23, $carbon::parse('2019-07')->getBusinessDaysInMonth());
    }

    public function testGetMonthBusinessDays()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('fr-national');
        $carbon::setTestNow('2020-01-16');

        self::assertCarbonList([
            '2020-01-02',
            '2020-01-03',
            '2020-01-06',
            '2020-01-07',
            '2020-01-08',
            '2020-01-09',
            '2020-01-10',
            '2020-01-13',
            '2020-01-14',
            '2020-01-15',
            '2020-01-16',
            '2020-01-17',
            '2020-01-20',
            '2020-01-21',
            '2020-01-22',
            '2020-01-23',
            '2020-01-24',
            '2020-01-27',
            '2020-01-28',
            '2020-01-29',
            '2020-01-30',
            '2020-01-31',
        ], $carbon::getMonthBusinessDays());
        self::assertCarbonList([
            '2019-05-02',
            '2019-05-03',
            '2019-05-06',
            '2019-05-07',
            '2019-05-09',
            '2019-05-10',
            '2019-05-13',
            '2019-05-14',
            '2019-05-15',
            '2019-05-16',
            '2019-05-17',
            '2019-05-20',
            '2019-05-21',
            '2019-05-22',
            '2019-05-23',
            '2019-05-24',
            '2019-05-27',
            '2019-05-28',
            '2019-05-29',
            '2019-05-31',
        ], $carbon::getMonthBusinessDays('2019-05'));
        self::assertCarbonList([
            '2019-05-02',
            '2019-05-03',
            '2019-05-06',
            '2019-05-07',
            '2019-05-09',
            '2019-05-10',
            '2019-05-13',
            '2019-05-14',
            '2019-05-15',
            '2019-05-16',
            '2019-05-17',
            '2019-05-20',
            '2019-05-21',
            '2019-05-22',
            '2019-05-23',
            '2019-05-24',
            '2019-05-27',
            '2019-05-28',
            '2019-05-29',
            '2019-05-31',
        ], $carbon::getMonthBusinessDays(new DateTime('2019-05-31 23:59:59.999999')));
        self::assertCarbonList([
            '2019-03-01',
            '2019-03-04',
            '2019-03-05',
            '2019-03-06',
            '2019-03-07',
            '2019-03-08',
            '2019-03-11',
            '2019-03-12',
            '2019-03-13',
            '2019-03-14',
            '2019-03-15',
            '2019-03-18',
            '2019-03-19',
            '2019-03-20',
            '2019-03-21',
            '2019-03-22',
            '2019-03-25',
            '2019-03-26',
            '2019-03-27',
            '2019-03-28',
            '2019-03-29',
        ], $carbon::parse('2019-03-01', 'Europe/Paris')->getMonthBusinessDays());
    }

    public function testHolidayCalculatorInterpolation()
    {
        $holidays = [];
        $calculator = new HolidayCalculator(2019, 'string', $holidays);
        $calculator->setOutputClass(static::CARBON_CLASS);

        self::assertNull($calculator->interpolateFixedDate(['ko']));
    }

    public function testFallbackRegionWithCustomHolidays()
    {
        $carbon = static::CARBON_CLASS;
        BusinessDay::enable($carbon, 'us-US', [
            'custom' => '2020-09-16',
        ]);

        self::assertTrue($carbon::parse('2020-9-16')->isHoliday());
    }

    public function testDataStorage()
    {
        $carbon = static::CARBON_CLASS;
        BusinessDay::enable($carbon, 'us-US', [
            'custom' => '2020-09-16',
        ]);
        $carbon::parse('2020-09-16')->setHolidayData([
            'info' => 'You may need to know...',
        ]);
        $carbon::parse('2020-10-13')->setHolidayData([
            'info' => 'Lost if not a holiday.',
        ]);
        $carbon::setHolidayDataById('christmas', [
            'info' => 'It may be cold in USA',
        ]);

        // Empty array if not filled
        self::assertSame([], $carbon::parse('2020-10-12')->getHolidayData());
        // null if not holiday
        self::assertNull($carbon::parse('2020-10-13')->getHolidayData());
        self::assertSame([
            'info' => 'You may need to know...',
        ], $carbon::getHolidayDataById('custom'));
        // Note that locale does not matter
        self::assertSame([
            'info' => 'It may be cold in USA',
        ], $carbon::parse('2020-12-25')->locale('fr')->getHolidayData());
        // Neither region if the same ID is used:
        $carbon::setHolidaysRegion('fr-national');
        self::assertSame([
            'info' => 'It may be cold in USA',
        ], $carbon::parse('2020-12-25')->locale('fr')->getHolidayData());
    }

    public function testExtraWorkDays()
    {
        $carbon = static::CARBON_CLASS;
        BusinessDay::enable($carbon, 'fr-national', null, [
            'working-sunday' => '03/01/2021',
        ]);

        self::assertTrue($carbon::parse('2021-01-03')->isBusinessDay());
        self::assertFalse($carbon::parse('2027-01-03')->isBusinessDay());
        self::assertFalse($carbon::parse('2021-07-14')->isBusinessDay());

        $carbon::setExtraWorkdays('fr-national', [
            'working-sunday' => '03/01/2027',
        ]);

        self::assertFalse($carbon::parse('2021-01-03')->isBusinessDay());
        self::assertTrue($carbon::parse('2027-01-03')->isBusinessDay());
        self::assertFalse($carbon::parse('2021-07-14')->isBusinessDay());
    }

    public function testMixedConditionsNotYetSupported()
    {
        self::expectExceptionObject(
            new InvalidArgumentException(
                'Mixed conditions are not supported for now, they must all target the same unit (for example: year)'
            )
        );
        $carbon = static::CARBON_CLASS;
        BusinessDay::enable($carbon, 'fr-national', null, [
            'mixed' => '= 09-01 if year > 1991 and day = Monday',
        ]);

        $carbon::parse('2024-09-01')->isBusinessDay();
    }

    public function testSetExtraWorkdayGetter()
    {
        $carbon = static::CARBON_CLASS;
        BusinessDay::enable($carbon, 'fr-national');

        $carbon::setExtraWorkdayGetter(function (string $region, CarbonInterface $self, callable $fallback) {
            // No change on odd years
            if ($self->year % 2) {
                return $fallback();
            }

            if ($self->format('m-d') === '02-02') {
                return 'Too even day';
            }

            return false;
        });

        self::assertFalse($carbon::parse('2021-02-02')->isExtraWorkday());
        self::assertFalse($carbon::parse('2021-02-02')->getExtraWorkdayId());
        self::assertTrue($carbon::parse('2022-02-02')->isExtraWorkday());
        self::assertSame('Too even day', $carbon::parse('2022-02-02')->getExtraWorkdayId());
    }

    public function testGetHolidaysAvailableRegions()
    {
        $carbon = static::CARBON_CLASS;
        BusinessDay::enable($carbon, 'fr-national');

        $actual = $carbon::getHolidaysAvailableRegions();

        self::assertIsArray($actual);
        self::assertContains('fr-national', $actual);
    }

    public function testGetHolidaysFallback()
    {
        $carbon = static::CARBON_CLASS;

        $date = $carbon::parse('2018-04-16 12:00:00')->settings([
            'macros' => [
                'getHolidays' => static function () {
                    throw new BadMethodCallException('Not locally defined');
                },
            ],
        ]);
        self::assertSame($date, $date->currentOrNextBusinessDay());

        $mixin = BusinessDay::enable($carbon, 'fr-national');
        $dateClass = $carbon === CarbonImmutable::class ? DateTimeImmutable::class : DateTime::class;
        $date = new $dateClass('2022-12-24 12:00:00');
        $strategy = static function (CarbonInterface $date) {
            return !$date->is('2022-12-25');
        };
        $next = clone $date->modify('+1 day');

        MixinConfigPropagator::setBusinessDayChecker($mixin, $date, $strategy);

        self::assertNull(MixinConfigPropagator::getBusinessDayChecker($mixin, $next));

        MixinConfigPropagator::propagate($date, $next);

        self::assertSame($strategy, MixinConfigPropagator::getBusinessDayChecker($mixin, $next));
    }
}
