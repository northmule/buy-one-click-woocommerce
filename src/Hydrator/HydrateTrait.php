<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Hydrator;

use Coderun\BuyOneClick\Exceptions\ObjectException;
use Coderun\BuyOneClick\Exceptions\VariablesException;
use Coderun\BuyOneClick\Utils\ManipulationsWithStrings;
use DateTime;
use ReflectionClass;
use ReflectionProperty;
use Generator;
use ReflectionType;

use function is_numeric;
use function floatval;
use function strval;
use function intval;
use function boolval;
use function is_object;
use function is_array;

trait HydrateTrait
{
    /**
     * @inheritDoc
     *
     * @param object $initialObject
     *
     * @return array
     */
    public function extractToArray(object $initialObject): array
    {
        $options = [];
        /**
 * @var ReflectionProperty $item
*/
        foreach ($this->getProperty($initialObject) as $item) {
            $propertyValue = $item->getValue($initialObject);
            if (is_array($propertyValue)) {
                $extractValue = [];
                foreach ($propertyValue as $value) {
                    if (is_object($value)) {
                        $value = $this->extractToArray($value);
                    }
                    $extractValue[] = $value;
                }
                $propertyValue = $extractValue;
            }
            $options[$item->getName()] = $propertyValue;
        }
        return $options;
    }

    /**
     * Массив в объект
     *
     * @param array  $data
     * @param object $entity
     *
     * @return object
     * @throws \Exception
     */
    public function hydrateArrayToObject(array $data, object $entity): object
    {
        $propertyMap = $this->getPropertyTypes($entity);
        foreach ($data as $propertyName => $propertyValue) {
            if (is_numeric($propertyName)) {
                throw VariablesException::valueIsNumeric();
            }
            $propertyName = ManipulationsWithStrings::snakeInCamelCase($propertyName);
            /** @var ReflectionType $propertyType */
            $propertyType = $propertyMap[$propertyName] ?? null;
            if ($propertyType == null) {
                throw VariablesException::valueIsNotDefined($propertyName);
            }
            $setter = sprintf('set%s', ucfirst($propertyName));
//            if (!method_exists($entity, $setter)) {
//                throw ObjectException::methodDoesNotExist($setter, $entity);
//            }
            $propertyValue = $this->typeConversions($propertyValue, $propertyType);
            $entity->{$setter}($propertyValue);
        }
        return $entity;
    }

    /**
     * @param object $object
     *
     * @return Generator<ReflectionProperty>
     */
    private function getProperty(object $object): Generator
    {
        $class = new ReflectionClass($object);
        foreach ($class->getProperties() as $property) {
            if ($property->isPrivate()) {
                continue;
            }
            if ($property->isProtected()) {
                $property->setAccessible(true);
            }
            yield $property;
        }
    }

    /**
     * Типы свойств из докблоков
     *
     * @param object $object
     *
     * @return array<string, ReflectionType>
     */
    private function getPropertyTypes(object $object): array
    {
        $typesMap = [];
        /**
 * @var ReflectionProperty $item
*/
        foreach ($this->getProperty($object) as $item) {
            $typeData = $item->getType();
            if ($typeData == null) {
                throw ObjectException::propertyTypeIsNotDefined();
            }
            $typesMap[$item->getName()] = $typeData;
        }
        return $typesMap;
    }

    /**
     * Преобразование строковых переменных в нужный тип
     * для значения сеттеров
     *
     * @param string|null    $variable
     * @param ReflectionType $type
     *
     * @return mixed
     * @throws \Exception
     */
    private function typeConversions($variable, ReflectionType $type)
    {
        if ($variable == null) {
            return $variable;
        }
        $acceptableTypes[] = $type->getName();
        if ($type->allowsNull()) {
            $acceptableTypes[] = 'null';
        }
        foreach ($acceptableTypes as $acceptableType) {
            if ($acceptableType === 'null' && empty($variable)) {
                return null;
            }
            switch ($acceptableType) {
                case 'int':
                    return intval($variable);
                case 'string':
                    return strval($variable);
                case 'bool':
                    return boolval($variable);
                case 'DateTime':
                    return new DateTime($variable);
                case 'float':
                    return floatval($variable);
                case 'null':
                    continue 2;
                case 'array':
                    return $variable;
                default:
                    throw VariablesException::variableWasNotExpected();
            }
        }
    }
}
