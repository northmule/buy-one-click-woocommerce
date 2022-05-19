<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Options;

use ReflectionClass;

trait UtilsTrait
{
    /**
     * Все поля в ассоциативный массив
     *
     * @return array
     */
    public function toArray(): array
    {
        $options = [];
    
        return $options;
    }
    
    /**
     * Все поля в ассоциативный массив настроек WordPress
     *
     * @return array
     */
    public function toArrayWp(): array
    {
        $options = [];
        
        return $options;
    }
    
    protected function getProperty(): \Generator
    {
        $class = new ReflectionClass();
        foreach ($class->getStaticProperties() as $property) {
            yield $property;
        }
    }
}