<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Hydrator;
use ReflectionClass;
use ReflectionProperty;
use Generator;

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
        /** @var ReflectionProperty $item */
        foreach ($this->getProperty($initialObject) as $item) {
            $options[$item->getName()] = $item->getValue($initialObject);
        }
        return $options;
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
}