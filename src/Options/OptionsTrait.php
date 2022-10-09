<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Options;

use Coderun\BuyOneClick\Exceptions\VariablesException;
use ReflectionClass;
use ReflectionProperty;
use Generator;

use function json_encode;
use function preg_match_all;

trait OptionsTrait
{
    /**
     * @inheritDoc
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $options = [];
        /**
         * @var ReflectionProperty $item
         */
        foreach ($this->getProperty() as $item) {
            $options[$item->getName()] = $item->getValue($this);
        }
        return $options;
    }
    
    /**
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * @inheritDoc
     *
     * @return array
     */
    public function toArrayWp(): array
    {
        $options = [];
        /**
         * @var ReflectionProperty $item
         */
        foreach ($this->getProperty() as $item) {
            $com = $item->getDocComment();
            preg_match_all('/@(\w+)\s+(.*)\r?\n/m', $com, $matches);
            $annotations = array_combine($matches[1], $matches[2]);
            if (!isset($annotations[self::DOC_OPTIONS_NAME])) {
                throw VariablesException::valueIsNotDefined(self::DOC_OPTIONS_NAME);
            }
            $optionsName = $annotations[self::DOC_OPTIONS_NAME];
            $options[$optionsName] = $item->getValue($this);
        }
        return $options;
    }

    /**
     * @inheritDoc
     *
     * @return array<string, array>
     */
    public function toArrayWpToSave(): array
    {
        return [$this->getRootKey() => $this->toArrayWp()];
    }

    /**
     * @return Generator<ReflectionProperty>
     */
    private function getProperty(): Generator
    {
        $class = new ReflectionClass($this);
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
