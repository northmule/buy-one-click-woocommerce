<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\SimpleDataObjects;

use Coderun\BuyOneClick\Exceptions\VariablesException;
use DateTime;
use Exception;
use ReflectionProperty;
use ReflectionClass;
use WC_Product;

/**
 * Class DataTransferObject
 */
abstract class DataTransferObject implements DataTransferObjectInterface
{
    /**
     * @param array<string, scalar> $parameters
     *
     * @throws Exception
     */
    public function __construct(array $parameters = [])
    {
        $class = new ReflectionClass(static::class);
        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            $property = $reflectionProperty->getName();
            if ($parameters[$property] === null) {
                $this->{$property} = null;
                continue;
            }
            $this->{$property} = $this->leadToType($parameters[$property], $reflectionProperty);
        }
    }

    /**
     * @param mixed              $value
     * @param ReflectionProperty $property
     *
     * @return bool|DateTime|float|int|string|null
     * @throws Exception
     */
    protected function leadToType($value, ReflectionProperty $property)
    {
        $propertyType = $property->getType();
        $acceptableTypes[] = $propertyType->getName();
        if ($propertyType->allowsNull()) {
            $acceptableTypes[] = 'null';
        }
        foreach ($acceptableTypes as $acceptableType) {
            if ($acceptableType === 'null' && empty($value)) {
                return null;
            }
            switch ($acceptableType) {
                case 'int':
                    return intval($value);
                case 'string':
                    return strval($value);
                case 'bool':
                    return boolval($value);
                case 'DateTime':
                    return new DateTime($value);
                case 'float':
                    return floatval($value);
                case 'null':
                    return null;
                case 'array':
                    return (array) $value;
                case WC_Product::class:
                    return $value;
                default:
                    throw VariablesException::variableWasNotExpected();
            }
        }
    }
}
