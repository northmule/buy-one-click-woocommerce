<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Exceptions;

/**
 * Class ObjectException
 *
 * @package Coderun\BuyOneClick\Exceptions
 */
class ObjectException extends BaseException implements ExceptionInterface
{
    /**
     * @param string $method
     * @param object $class
     *
     * @return VariablesException
     */
    public static function methodDoesNotExist(string $method, object $class): ObjectException
    {
        return new self(
            sprintf(
                'The method %s does not exist: %s',
                $method,
                get_class($class)
            ),
        );
    }

    /**
     * @return ObjectException
     */
    public static function propertyTypeIsNotDefined(): ObjectException
    {
        return new self('the property type is not defined');
    }

    /**
     * @param string $setter
     * @param string $className
     *
     * @return ObjectException
     */
    public static function setterDoesNotExist(string $setter, string $className): ObjectException
    {
        return new self(
            sprintf(
                'The setter %s does not exist: %s',
                $setter,
                $className
            ),
        );
    }
}
