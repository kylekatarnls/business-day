<?php

declare(strict_types=1);

namespace Cmixin\BusinessDay\PHPStan;

use Carbon\CarbonInterface;
use PHPStan\Reflection\ClassMemberReflection;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\FunctionVariant;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParameterReflection;
use PHPStan\Reflection\PassedByReference;
use PHPStan\TrinaryLogic;
use PHPStan\Type\ArrayType;
use PHPStan\Type\BooleanType;
use PHPStan\Type\CallableType;
use PHPStan\Type\Generic\TemplateTypeMap;
use PHPStan\Type\IntegerType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;

/**
 * PHPStan method reflection for cmixin/business-day methods.
 */
final class BusinessDayMethodReflection implements MethodReflection
{
    /**
     * @var ClassReflection
     */
    private $classReflection;

    /**
     * @var string
     */
    private $methodName;

    /**
     * @var Type
     */
    private $returnType;

    public function __construct(
        ClassReflection $classReflection,
        string $methodName,
        Type $returnType
    ) {
        $this->classReflection = $classReflection;
        $this->methodName = $methodName;
        $this->returnType = $returnType;
    }

    public function getDeclaringClass(): ClassReflection
    {
        return $this->classReflection;
    }

    public function isStatic(): bool
    {
        return false;
    }

    public function isPrivate(): bool
    {
        return false;
    }

    public function isPublic(): bool
    {
        return true;
    }

    public function getDocComment(): ?string
    {
        return null;
    }

    public function getName(): string
    {
        return $this->methodName;
    }

    public function getPrototype(): ClassMemberReflection
    {
        return $this;
    }

    public function getVariants(): array
    {
        return [
            new FunctionVariant(
                TemplateTypeMap::createEmpty(),
                null,
                $this->getParameters(),
                $this->isVariadic(),
                $this->returnType
            ),
        ];
    }

    public function isDeprecated(): TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }

    public function getDeprecatedDescription(): ?string
    {
        return null;
    }

    public function isFinal(): TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }

    public function isInternal(): TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }

    public function getThrowType(): ?Type
    {
        return null;
    }

    public function hasSideEffects(): TrinaryLogic
    {
        return TrinaryLogic::createMaybe();
    }

    /**
     * @return list<ParameterReflection>
     */
    private function getParameters(): array
    {
        $parameters = [];

        switch ($this->methodName) {
            // Methods with $days parameter
            case 'addBusinessDays':
            case 'subBusinessDays':
            case 'subtractBusinessDays':
                $parameters[] = $this->createParameter('days', new IntegerType(), false, true, 1);
                $parameters[] = $this->createParameter('date', new UnionType([
                    new ObjectType(CarbonInterface::class),
                    new ObjectType('\DateTime'),
                    new NullType(),
                ]), false, true, null);
                break;

                // Methods with $other date parameter
            case 'diffInBusinessDays':
                $parameters[] = $this->createParameter('other', new UnionType([
                    new ObjectType(CarbonInterface::class),
                    new ObjectType('\DateTime'),
                    new NullType(),
                ]), false, true, null);
                break;

                // Methods with optional $date parameter
            case 'getBusinessDaysInMonth':
            case 'getMonthBusinessDays':
                $parameters[] = $this->createParameter('date', new UnionType([
                    new ObjectType(CarbonInterface::class),
                    new ObjectType('\DateTime'),
                    new NullType(),
                ]), false, true, null);
                break;

                // Holiday name method with date/locale parameters
            case 'getHolidayName':
                $parameters[] = $this->createParameter('date', new UnionType([
                    new ObjectType(CarbonInterface::class),
                    new ObjectType('\DateTime'),
                    new StringType(),
                    new NullType(),
                ]), false, true, null);
                $parameters[] = $this->createParameter('locale', new UnionType([
                    new StringType(),
                    new NullType(),
                ]), false, true, null);
                break;

                // Region setter
            case 'setHolidaysRegion':
            case 'initializeHolidaysRegion':
                $parameters[] = $this->createParameter('region', new StringType(), false, false);
                break;

                // Set holidays method
            case 'setHolidays':
            case 'setExtraWorkdays':
                $parameters[] = $this->createParameter('region', new StringType(), false, false);
                $parameters[] = $this->createParameter('holidays', new ArrayType(new MixedType(), new MixedType()), false, false);
                break;

                // Add holiday method
            case 'addHoliday':
            case 'addExtraWorkday':
                $parameters[] = $this->createParameter('region', new StringType(), false, false);
                $parameters[] = $this->createParameter('holiday', new UnionType([
                    new StringType(),
                    new CallableType(),
                ]), false, false);
                $parameters[] = $this->createParameter('holidayId', new UnionType([
                    new StringType(),
                    new NullType(),
                ]), false, true, null);
                $parameters[] = $this->createParameter('name', new UnionType([
                    new StringType(),
                    new NullType(),
                ]), false, true, null);
                if ($this->methodName === 'addHoliday') {
                    $parameters[] = $this->createParameter('observed', new UnionType([
                        new BooleanType(),
                        new NullType(),
                    ]), false, true, null);
                }
                break;

                // Holiday observer methods
            case 'observeHoliday':
            case 'unobserveHoliday':
                $parameters[] = $this->createParameter('holidayId', new StringType(), false, false);
                break;

                // Multiple holiday observer methods
            case 'observeHolidays':
            case 'unobserveHolidays':
                $parameters[] = $this->createParameter('holidayIds', new ArrayType(new IntegerType(), new StringType()), false, false);
                break;

                // isObservedHoliday method
            case 'isObservedHoliday':
                $parameters[] = $this->createParameter('holidayId', new UnionType([
                    new StringType(),
                    new NullType(),
                ]), false, true, null);
                $parameters[] = $this->createParameter('date', new UnionType([
                    new ObjectType(CarbonInterface::class),
                    new ObjectType('\DateTime'),
                    new NullType(),
                ]), false, true, null);
                break;

                // Custom setter methods
            case 'setBusinessDayChecker':
                $parameters[] = $this->createParameter('checkCallback', new UnionType([
                    new CallableType(),
                    new NullType(),
                ]), false, true, null);
                break;

            case 'setHolidayGetter':
            case 'setExtraWorkdayGetter':
                $parameters[] = $this->createParameter('holidayGetter', new UnionType([
                    new CallableType(),
                    new NullType(),
                ]), false, false);
                break;

            case 'setObservedHolidaysZone':
                $parameters[] = $this->createParameter('zone', new UnionType([
                    new StringType(),
                    new NullType(),
                ]), false, false);
                break;

            case 'setHolidayObserveStatus':
                $parameters[] = $this->createParameter('holidayId', new StringType(), false, false);
                $parameters[] = $this->createParameter('observed', new BooleanType(), false, false);
                break;

            case 'setHolidayName':
                $parameters[] = $this->createParameter('holidayId', new StringType(), false, false);
                $parameters[] = $this->createParameter('name', new StringType(), false, false);
                $parameters[] = $this->createParameter('locale', new UnionType([
                    new StringType(),
                    new NullType(),
                ]), false, true, null);
                break;

            case 'addHolidays':
                $parameters[] = $this->createParameter('region', new StringType(), false, false);
                $parameters[] = $this->createParameter('holidays', new ArrayType(new MixedType(), new MixedType()), false, false);
                break;

                // Methods with no parameters
            case 'addBusinessDay':
            case 'subBusinessDay':
            case 'subtractBusinessDay':
            case 'nextBusinessDay':
            case 'currentOrNextBusinessDay':
            case 'previousBusinessDay':
            case 'currentOrPreviousBusinessDay':
            case 'isBusinessDay':
            case 'isHoliday':
            case 'isExtraWorkday':
            case 'getHolidayId':
            case 'getExtraWorkdayId':
            case 'getObservedHolidaysZone':
            case 'getHolidaysRegion':
            case 'getHolidays':
            case 'getExtraWorkdays':
            case 'getYearHolidays':
            case 'getHolidayNamesDictionary':
            case 'getHolidaysAvailableRegions':
            case 'resetHolidays':
            case 'observeAllHolidays':
            case 'unobserveAllHolidays':
            case 'checkObservedHoliday':
            case 'isDateTimeInstance':
                // No parameters
                break;

                // These methods have complex internal usage, providing basic signatures
            default:
                // For any methods not explicitly handled, return empty parameters
                break;
        }

        return $parameters;
    }

    /**
     * Create a parameter reflection.
     */
    private function createParameter(
        string $name,
        Type $type,
        bool $passedByReference = false,
        bool $optional = false,
        $defaultValue = null
    ): ParameterReflection {
        return new class($name, $type, $passedByReference, $optional, $defaultValue) implements ParameterReflection {
            /**
             * @var string
             */
            private $name;

            /**
             * @var Type
             */
            private $type;

            /**
             * @var bool
             */
            private $passedByReference;

            /**
             * @var bool
             */
            private $optional;

            /**
             * @var mixed
             */
            private $defaultValue;

            public function __construct(string $name, Type $type, bool $passedByReference, bool $optional, $defaultValue)
            {
                $this->name = $name;
                $this->type = $type;
                $this->passedByReference = $passedByReference;
                $this->optional = $optional;
                $this->defaultValue = $defaultValue;
            }

            public function getName(): string
            {
                return $this->name;
            }

            public function isOptional(): bool
            {
                return $this->optional;
            }

            public function getType(): Type
            {
                return $this->type;
            }

            public function passedByReference(): PassedByReference
            {
                return $this->passedByReference ? PassedByReference::createCreatesNewVariable() : PassedByReference::createNo();
            }

            public function isVariadic(): bool
            {
                return false;
            }

            public function getDefaultValue(): ?Type
            {
                if (!$this->optional || $this->defaultValue === null) {
                    return null;
                }

                if (is_int($this->defaultValue)) {
                    return new IntegerType();
                }

                if (is_string($this->defaultValue)) {
                    return new StringType();
                }

                if (is_bool($this->defaultValue)) {
                    return new BooleanType();
                }

                return new NullType();
            }
        };
    }

    private function isVariadic(): bool
    {
        return false;
    }
}
