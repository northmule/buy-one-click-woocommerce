<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Hydrator;

interface HydratorInterface
{
    /**
     * Создание объекта на основе данных из массива
     *
     * @param array  $initialData
     * @param object $destinationObject
     *
     * @return object
     */
    public function hydrate(array $initialData, object $destinationObject): object;

    /**
     * Распакова объекта в массив
     *
     * @param object $initialObject
     *
     * @return array
     */
    public function extractToArray(object $initialObject): array;
}
