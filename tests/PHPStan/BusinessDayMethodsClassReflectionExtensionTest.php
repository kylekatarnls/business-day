<?php

namespace Tests\PHPStan;

use Cmixin\BusinessDay\PHPStan\BusinessDayMethodsClassReflectionExtension;
use PHPUnit\Framework\TestCase;

class BusinessDayMethodsClassReflectionExtensionTest extends TestCase
{
    /**
     * @var BusinessDayMethodsClassReflectionExtension
     */
    private $extension;

    protected function setUp(): void
    {
        $this->extension = new BusinessDayMethodsClassReflectionExtension();
    }

    public function testExtensionCanBeInstantiated(): void
    {
        $this->assertInstanceOf(BusinessDayMethodsClassReflectionExtension::class, $this->extension);
    }

    public function testExtensionRecognizesKnownMethods(): void
    {
        $reflection = new \ReflectionClass(BusinessDayMethodsClassReflectionExtension::class);
        $methodReturnTypes = $reflection->getConstant('METHOD_RETURN_TYPES');

        $this->assertIsArray($methodReturnTypes);
        $this->assertArrayHasKey('addBusinessDays', $methodReturnTypes);
        $this->assertArrayHasKey('isBusinessDay', $methodReturnTypes);
        $this->assertArrayHasKey('getHolidays', $methodReturnTypes);
    }

    public function testExtensionHasRequiredInterfaceMethods(): void
    {
        $this->assertTrue(method_exists($this->extension, 'hasMethod'));
        $this->assertTrue(method_exists($this->extension, 'getMethod'));
    }

    /**
     * @dataProvider businessDayMethodsProvider
     */
    public function testExtensionRecognizesBusinessDayMethods(string $methodName): void
    {
        $reflection = new \ReflectionClass(BusinessDayMethodsClassReflectionExtension::class);
        $methodReturnTypes = $reflection->getConstant('METHOD_RETURN_TYPES');

        $this->assertIsArray($methodReturnTypes);
        $this->assertArrayHasKey(
            $methodName,
            $methodReturnTypes,
            "Method '$methodName' should be recognized as a business day method"
        );
    }

    public function testExtensionHasAllExpectedMethods(): void
    {
        $reflection = new \ReflectionClass(BusinessDayMethodsClassReflectionExtension::class);
        $methodReturnTypes = $reflection->getConstant('METHOD_RETURN_TYPES');

        $this->assertIsArray($methodReturnTypes);
        $this->assertGreaterThan(
            60,
            count($methodReturnTypes),
            'Extension should recognize more than 60 business day methods'
        );
    }

    public function testExtensionReturnTypesAreValid(): void
    {
        $reflection = new \ReflectionClass(BusinessDayMethodsClassReflectionExtension::class);
        $methodReturnTypes = $reflection->getConstant('METHOD_RETURN_TYPES');

        $validReturnTypes = ['fluent', 'bool', 'int', 'array', 'string|null', 'string|false', 'callable'];

        foreach ($methodReturnTypes as $method => $returnType) {
            $this->assertContains(
                $returnType,
                $validReturnTypes,
                "Method '$method' has invalid return type '$returnType'"
            );
        }
    }

    /**
     * @return array<array{string}>
     */
    public function businessDayMethodsProvider(): array
    {
        return [
            // Fluent methods
            ['addBusinessDays'],
            ['addBusinessDay'],
            ['subBusinessDays'],
            ['subtractBusinessDays'],
            ['subBusinessDay'],
            ['subtractBusinessDay'],
            ['nextBusinessDay'],
            ['currentOrNextBusinessDay'],
            ['previousBusinessDay'],
            ['currentOrPreviousBusinessDay'],
            ['setBusinessDayChecker'],
            ['setObservedHolidaysZone'],
            ['setHolidayObserveStatus'],
            ['getObserveHolidayMethod'],
            ['observeHoliday'],
            ['unobserveHoliday'],
            ['observeHolidays'],
            ['unobserveHolidays'],
            ['observeAllHolidays'],
            ['unobserveAllHolidays'],
            ['setHolidayGetter'],
            ['setExtraWorkdayGetter'],
            ['setHolidaysRegion'],
            ['setHolidays'],
            ['setExtraWorkdays'],
            ['resetHolidays'],
            ['initializeHolidaysRegion'],
            ['pushToBDList'],
            ['pushHoliday'],
            ['pushWorkday'],
            ['setHolidayName'],
            ['addHoliday'],
            ['addExtraWorkday'],
            ['addHolidays'],
            ['enable'],
            ['setHolidayDataById'],
            ['setHolidayData'],

            // Boolean methods
            ['isBusinessDay'],
            ['isHoliday'],
            ['isExtraWorkday'],
            ['checkObservedHoliday'],
            ['isObservedHoliday'],
            ['isDateTimeInstance'],

            // Integer methods
            ['diffInBusinessDays'],
            ['getBusinessDaysInMonth'],

            // Array methods
            ['getMonthBusinessDays'],
            ['getHolidaysAvailableRegions'],
            ['getBDDaysList'],
            ['getHolidays'],
            ['getExtraWorkdays'],
            ['getHolidayNamesDictionary'],
            ['getYearHolidays'],
            ['unpackHoliday'],
            ['checkHoliday'],
            ['swapDateTimeParam'],
            ['getHolidayDataById'],
            ['getHolidays'],

            // Nullable string methods
            ['getObservedHolidaysZone'],
            ['getHolidaysRegion'],

            // String or false methods
            ['getDBDayId'],
            ['getHolidayId'],
            ['getExtraWorkdayId'],
            ['getHolidayName'],
            ['standardizeHolidaysRegion'],

            // Callable
            ['getYearHolidaysNextFunction'],
        ];
    }
}
