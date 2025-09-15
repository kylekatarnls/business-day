<?php

namespace Tests\PHPStan;

use Cmixin\BusinessDay\PHPStan\BusinessDayMethodReflection;
use PHPUnit\Framework\TestCase;

class BusinessDayMethodReflectionTest extends TestCase
{
    public function testBusinessDayMethodReflectionClassExists(): void
    {
        $this->assertTrue(class_exists(BusinessDayMethodReflection::class));
    }

    public function testBusinessDayMethodReflectionImplementsCorrectInterface(): void
    {
        $reflection = new \ReflectionClass(BusinessDayMethodReflection::class);
        $this->assertTrue($reflection->implementsInterface('PHPStan\Reflection\MethodReflection'));
    }

    public function testBusinessDayMethodReflectionHasRequiredMethods(): void
    {
        $reflection = new \ReflectionClass(BusinessDayMethodReflection::class);

        $requiredMethods = [
            'getDeclaringClass',
            'isStatic',
            'isPrivate',
            'isPublic',
            'getDocComment',
            'getName',
            'getPrototype',
            'getVariants',
            'isDeprecated',
            'getDeprecatedDescription',
            'isFinal',
            'isInternal',
            'getThrowType',
            'hasSideEffects',
        ];

        foreach ($requiredMethods as $methodName) {
            $this->assertTrue(
                $reflection->hasMethod($methodName),
                "BusinessDayMethodReflection should have method '$methodName'"
            );
        }
    }

    public function testBusinessDayMethodReflectionHasParameterHandling(): void
    {
        $reflection = new \ReflectionClass(BusinessDayMethodReflection::class);

        // Check that it has private methods for parameter handling
        $this->assertTrue(
            $reflection->hasMethod('getParameters'),
            'BusinessDayMethodReflection should have getParameters method'
        );

        $this->assertTrue(
            $reflection->hasMethod('createParameter'),
            'BusinessDayMethodReflection should have createParameter method'
        );

        $this->assertTrue(
            $reflection->hasMethod('isVariadic'),
            'BusinessDayMethodReflection should have isVariadic method'
        );
    }

    public function testBusinessDayMethodReflectionConstructorSignature(): void
    {
        $reflection = new \ReflectionClass(BusinessDayMethodReflection::class);
        $constructor = $reflection->getConstructor();

        $this->assertNotNull($constructor);

        $parameters = $constructor->getParameters();
        $this->assertCount(3, $parameters);

        $this->assertEquals('classReflection', $parameters[0]->getName());
        $this->assertEquals('methodName', $parameters[1]->getName());
        $this->assertEquals('returnType', $parameters[2]->getName());
    }

    public function testBusinessDayMethodReflectionUsesCorrectPHPStanTypes(): void
    {
        // Verify that the class imports the correct PHPStan types
        $fileContent = file_get_contents(__DIR__.'/../../src/Cmixin/BusinessDay/PHPStan/BusinessDayMethodReflection.php');

        $this->assertNotFalse(strpos($fileContent, 'use PHPStan\Reflection\ClassReflection;'));
        $this->assertNotFalse(strpos($fileContent, 'use PHPStan\Reflection\MethodReflection;'));
        $this->assertNotFalse(strpos($fileContent, 'use PHPStan\Type\Type;'));
        $this->assertNotFalse(strpos($fileContent, 'use PHPStan\Type\IntegerType;'));
        $this->assertNotFalse(strpos($fileContent, 'use PHPStan\Type\StringType;'));
        $this->assertNotFalse(strpos($fileContent, 'use PHPStan\Type\BooleanType;'));
        $this->assertNotFalse(strpos($fileContent, 'use PHPStan\Type\ArrayType;'));
        $this->assertNotFalse(strpos($fileContent, 'use PHPStan\Type\UnionType;'));
    }
}
