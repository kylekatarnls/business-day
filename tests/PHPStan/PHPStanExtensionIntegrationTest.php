<?php

namespace Tests\PHPStan;

use Cmixin\BusinessDay\PHPStan\BusinessDayMethodsClassReflectionExtension;
use PHPStan\Reflection\ClassReflection;
use PHPUnit\Framework\TestCase;

class PHPStanExtensionIntegrationTest extends TestCase
{
    /**
     * @var BusinessDayMethodsClassReflectionExtension
     */
    private $extension;

    protected function setUp(): void
    {
        if (!class_exists(ClassReflection::class)) {
            $this->markTestSkipped('PHPStan is not available');
        }

        $this->extension = new BusinessDayMethodsClassReflectionExtension();
    }

    public function testExtensionImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf('PHPStan\Reflection\MethodsClassReflectionExtension', $this->extension);
    }

    public function testExtensionHasRequiredMethods(): void
    {
        $reflection = new \ReflectionClass(BusinessDayMethodsClassReflectionExtension::class);
        
        $this->assertTrue($reflection->hasMethod('hasMethod'));
        $this->assertTrue($reflection->hasMethod('getMethod'));
    }

    /**
     * @dataProvider methodReturnTypeProvider
     */
    public function testMethodReturnTypesAreCorrect(string $methodName, string $expectedReturnType): void
    {
        $reflection = new \ReflectionClass(BusinessDayMethodsClassReflectionExtension::class);
        $methodReturnTypes = $reflection->getConstant('METHOD_RETURN_TYPES');
        
        $this->assertArrayHasKey($methodName, $methodReturnTypes,
            "Method '$methodName' should be defined in METHOD_RETURN_TYPES");
            
        $this->assertEquals($expectedReturnType, $methodReturnTypes[$methodName],
            "Method '$methodName' should have return type '$expectedReturnType'");
    }

    public function testExtensionFilesExist(): void
    {
        $extensionNeonPath = __DIR__ . '/../../extension.neon';
        $this->assertFileExists($extensionNeonPath, 'PHPStan extension.neon file should exist');

        $extensionContent = file_get_contents($extensionNeonPath);
        $this->assertNotFalse(strpos($extensionContent, 'BusinessDayMethodsClassReflectionExtension'),
            'extension.neon should reference BusinessDayMethodsClassReflectionExtension');
    }

    public function testAllBusinessDayMethodsHaveValidReturnTypes(): void
    {
        $reflection = new \ReflectionClass(BusinessDayMethodsClassReflectionExtension::class);
        $methodReturnTypes = $reflection->getConstant('METHOD_RETURN_TYPES');
        
        $validReturnTypes = ['fluent', 'bool', 'int', 'array', 'string|null', 'string|false', 'callable'];
        
        foreach ($methodReturnTypes as $method => $returnType) {
            $this->assertContains($returnType, $validReturnTypes,
                "Method '$method' has invalid return type '$returnType'");
        }
        
        // Test that we have methods of each type
        $returnTypeValues = array_values($methodReturnTypes);
        foreach ($validReturnTypes as $validType) {
            $this->assertContains($validType, $returnTypeValues,
                "Should have at least one method with return type '$validType'");
        }
    }

    public function testExtensionHasComprehensiveMethodCoverage(): void
    {
        $reflection = new \ReflectionClass(BusinessDayMethodsClassReflectionExtension::class);
        $methodReturnTypes = $reflection->getConstant('METHOD_RETURN_TYPES');
        
        // Check that we have a good coverage of business day methods
        $this->assertGreaterThan(60, count($methodReturnTypes),
            'Extension should support more than 60 business day methods');
            
        // Check for key methods that should definitely be present
        $essentialMethods = [
            'isBusinessDay',
            'isHoliday',
            'addBusinessDays',
            'nextBusinessDay',
            'diffInBusinessDays',
            'getBusinessDaysInMonth',
            'setHolidaysRegion',
            'getHolidays'
        ];
        
        foreach ($essentialMethods as $method) {
            $this->assertArrayHasKey($method, $methodReturnTypes,
                "Essential method '$method' should be supported by the extension");
        }
    }

    /**
     * @return array<array{string, string}>
     */
    public function methodReturnTypeProvider(): array
    {
        return [
            // Fluent methods (should return the same class)
            ['addBusinessDays', 'fluent'],
            ['nextBusinessDay', 'fluent'],
            ['setHolidaysRegion', 'fluent'],
            ['observeHoliday', 'fluent'],
            
            // Boolean methods
            ['isBusinessDay', 'bool'],
            ['isHoliday', 'bool'],
            ['isExtraWorkday', 'bool'],
            
            // Integer methods
            ['diffInBusinessDays', 'int'],
            ['getBusinessDaysInMonth', 'int'],
            
            // Array methods
            ['getMonthBusinessDays', 'array'],
            ['getHolidays', 'array'],
            ['getYearHolidays', 'array'],
            
            // String|null methods
            ['getObservedHolidaysZone', 'string|null'],
            ['getHolidaysRegion', 'string|null'],
            
            // String|false methods
            ['getHolidayId', 'string|false'],
            ['getHolidayName', 'string|false'],
            
            // Callable methods
            ['getYearHolidaysNextFunction', 'callable'],
        ];
    }
}