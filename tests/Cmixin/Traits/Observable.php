<?php

namespace Tests\Cmixin\Traits;

trait Observable
{
    /**
     * @group i
     */
    public function testObservedHolidaysZone()
    {
        $carbon = static::CARBON_CLASS;
        self::assertSame('default', $carbon::getObservedHolidaysZone());
        self::assertSame('default', $carbon::now()->getObservedHolidaysZone());
        self::assertNull($carbon::setObservedHolidaysZone('my-company'));
        self::assertSame('my-company', $carbon::getObservedHolidaysZone());
        self::assertSame('my-company', $carbon::now()->getObservedHolidaysZone());
        $now = $carbon::now();
        self::assertSame($now, $now->setObservedHolidaysZone('foobar'));
        self::assertSame('foobar', $carbon::getObservedHolidaysZone());
        self::assertSame('foobar', $carbon::now()->getObservedHolidaysZone());
    }

    public function testObserveHolidays()
    {
        $carbon = static::CARBON_CLASS;
        $carbon::setHolidaysRegion('fr-national');
        self::assertFalse($carbon::isObservedHoliday('new-year'));
        self::assertFalse($carbon::isObservedHoliday('christmas'));
        self::assertFalse($carbon::parse('2018-01-01')->isObservedHoliday());
        self::assertFalse($carbon::parse('2018-12-25')->isObservedHoliday());
        self::assertFalse($carbon::parse('2018-12-26')->isObservedHoliday());
        $carbon::setTestNow('2018-12-26');
        self::assertFalse($carbon::isObservedHoliday());
        $carbon::setTestNow('2018-12-25');
        self::assertFalse($carbon::isObservedHoliday());
        self::assertFalse($carbon::isObservedHoliday(new \DateTime('2018-12-25')));
        $carbon::observeHoliday('christmas');
        self::assertFalse($carbon::isObservedHoliday('new-year'));
        self::assertTrue($carbon::isObservedHoliday('christmas'));
        self::assertFalse($carbon::parse('2018-01-01')->isObservedHoliday());
        self::assertTrue($carbon::parse('2018-12-25')->isObservedHoliday());
        self::assertFalse($carbon::parse('2018-12-26')->isObservedHoliday());
        $carbon::setTestNow('2018-12-26');
        self::assertFalse($carbon::isObservedHoliday());
        $carbon::setTestNow('2018-12-25');
        self::assertTrue($carbon::isObservedHoliday());
        self::assertTrue($carbon::isObservedHoliday(new \DateTime('2018-12-25')));
        $carbon::unobserveHoliday('christmas');
        self::assertFalse($carbon::isObservedHoliday('new-year'));
        self::assertFalse($carbon::isObservedHoliday('christmas'));
        self::assertFalse($carbon::parse('2018-01-01')->isObservedHoliday());
        self::assertFalse($carbon::parse('2018-12-25')->isObservedHoliday());
        self::assertFalse($carbon::parse('2018-12-26')->isObservedHoliday());
        $carbon::observeAllHolidays();
        self::assertTrue($carbon::isObservedHoliday('new-year'));
        self::assertTrue($carbon::isObservedHoliday('christmas'));
        self::assertTrue($carbon::parse('2018-01-01')->isObservedHoliday());
        self::assertTrue($carbon::parse('2018-12-25')->isObservedHoliday());
        self::assertFalse($carbon::parse('2018-12-26')->isObservedHoliday());
        $carbon::unobserveHoliday('christmas');
        self::assertTrue($carbon::isObservedHoliday('new-year'));
        self::assertFalse($carbon::isObservedHoliday('christmas'));
        self::assertTrue($carbon::parse('2018-01-01')->isObservedHoliday());
        self::assertFalse($carbon::parse('2018-12-25')->isObservedHoliday());
        self::assertFalse($carbon::parse('2018-12-26')->isObservedHoliday());
        $carbon::unobserveAllHolidays();
        self::assertFalse($carbon::isObservedHoliday('new-year'));
        self::assertFalse($carbon::isObservedHoliday('christmas'));
        self::assertFalse($carbon::parse('2018-01-01')->isObservedHoliday());
        self::assertFalse($carbon::parse('2018-12-25')->isObservedHoliday());
        self::assertFalse($carbon::parse('2018-12-26')->isObservedHoliday());
        $carbon::observeHolidays(['christmas', 'new-year']);
        self::assertTrue($carbon::isObservedHoliday('new-year'));
        self::assertTrue($carbon::isObservedHoliday('christmas'));
        self::assertTrue($carbon::parse('2018-01-01')->isObservedHoliday());
        self::assertTrue($carbon::parse('2018-12-25')->isObservedHoliday());
        self::assertFalse($carbon::parse('2018-12-26')->isObservedHoliday());
    }
}
