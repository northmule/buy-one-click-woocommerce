<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Hydrator;

/**
 * Class Common
 *
 * @package Coderun\BuyOneClick\Hydrator
 */
class CommonHydrator implements HydratorInterface
{
    use HydrateTrait;

    /**
     * @inheritDoc
     *
     * @param array  $initialData
     * @param object $destinationObject
     *
     * @return object
     */
    public function hydrate(
        array $initialData,
        object $destinationObject
    ): object {
        return $destinationObject; // todo
    }
}
