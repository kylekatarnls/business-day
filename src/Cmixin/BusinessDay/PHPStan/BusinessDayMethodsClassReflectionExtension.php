<?php

declare(strict_types=1);

namespace Cmixin\BusinessDay\PHPStan;

use Carbon\CarbonInterface;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Type\ArrayType;
use PHPStan\Type\BooleanType;
use PHPStan\Type\CallableType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;

/**
 * PHPStan extension for cmixin/business-day methods.
 */
final class BusinessDayMethodsClassReflectionExtension implements MethodsClassReflectionExtension
{
    /**
     * @var array<string, string>
     */
    private const METHOD_RETURN_TYPES = [
        // Fluent methods (return the same instance type)
        'addBusinessDays'              => 'fluent',
        'addBusinessDay'               => 'fluent',
        'subBusinessDays'              => 'fluent',
        'subtractBusinessDays'         => 'fluent',
        'subBusinessDay'               => 'fluent',
        'subtractBusinessDay'          => 'fluent',
        'nextBusinessDay'              => 'fluent',
        'currentOrNextBusinessDay'     => 'fluent',
        'previousBusinessDay'          => 'fluent',
        'currentOrPreviousBusinessDay' => 'fluent',
        'setBusinessDayChecker'        => 'fluent',
        'setObservedHolidaysZone'      => 'fluent',
        'setHolidayObserveStatus'      => 'fluent',
        'getObserveHolidayMethod'      => 'fluent',
        'observeHoliday'               => 'fluent',
        'unobserveHoliday'             => 'fluent',
        'observeHolidays'              => 'fluent',
        'unobserveHolidays'            => 'fluent',
        'observeAllHolidays'           => 'fluent',
        'unobserveAllHolidays'         => 'fluent',
        'setHolidayGetter'             => 'fluent',
        'setExtraWorkdayGetter'        => 'fluent',
        'setHolidaysRegion'            => 'fluent',
        'setHolidays'                  => 'fluent',
        'setExtraWorkdays'             => 'fluent',
        'resetHolidays'                => 'fluent',
        'initializeHolidaysRegion'     => 'fluent',
        'pushToBDList'                 => 'fluent',
        'pushHoliday'                  => 'fluent',
        'pushWorkday'                  => 'fluent',
        'setHolidayName'               => 'fluent',
        'addHoliday'                   => 'fluent',
        'addExtraWorkday'              => 'fluent',
        'addHolidays'                  => 'fluent',
        'enable'                       => 'fluent',
        'setHolidayDataById'           => 'fluent',
        'setHolidayData'               => 'fluent',

        // Boolean methods
        'isBusinessDay'        => 'bool',
        'isHoliday'            => 'bool',
        'isExtraWorkday'       => 'bool',
        'checkObservedHoliday' => 'bool',
        'isObservedHoliday'    => 'bool',
        'isDateTimeInstance'   => 'bool',

        // Integer methods
        'diffInBusinessDays'     => 'int',
        'getBusinessDaysInMonth' => 'int',

        // Array methods
        'getMonthBusinessDays'        => 'array',
        'getHolidaysAvailableRegions' => 'array',
        'getBDDaysList'               => 'array',
        'getHolidays'                 => 'array',
        'getExtraWorkdays'            => 'array',
        'getHolidayNamesDictionary'   => 'array',
        'getYearHolidays'             => 'array',
        'unpackHoliday'               => 'array',
        'checkHoliday'                => 'array',
        'swapDateTimeParam'           => 'array',
        'getHolidayDataById'          => 'array',
        'getHolidayData'              => 'array',

        // Nullable string methods
        'getObservedHolidaysZone' => 'string|null',
        'getHolidaysRegion'       => 'string|null',

        // String or false methods
        'getDBDayId'                => 'string|false',
        'getHolidayId'              => 'string|false',
        'getExtraWorkdayId'         => 'string|false',
        'getHolidayName'            => 'string|false',
        'standardizeHolidaysRegion' => 'string|false',

        // Callable
        'getYearHolidaysNextFunction' => 'callable',
    ];

    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        // Only apply to Carbon classes
        if ($classReflection->getName() !== CarbonInterface::class && !$classReflection->isSubclassOf(CarbonInterface::class)) {
            return false;
        }

        return isset(self::METHOD_RETURN_TYPES[$methodName]);
    }

    public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflection
    {
        $returnType = $this->getReturnType($classReflection, $methodName);

        return new BusinessDayMethodReflection(
            $classReflection,
            $methodName,
            $returnType
        );
    }

    private function getReturnType(ClassReflection $classReflection, string $methodName): Type
    {
        $returnTypeString = self::METHOD_RETURN_TYPES[$methodName] ?? 'mixed';

        switch ($returnTypeString) {
            case 'fluent':
                return new ObjectType($classReflection->getName());

            case 'bool':
                return new BooleanType();

            case 'int':
                return new IntegerType();

            case 'array':
                return new ArrayType(new MixedType(), new MixedType());

            case 'string|null':
                return new UnionType([new StringType(), new NullType()]);

            case 'string|false':
                return new UnionType([new StringType(), new BooleanType()]);

            case 'callable':
                return new CallableType();

            default:
                return new MixedType();
        }
    }
}
