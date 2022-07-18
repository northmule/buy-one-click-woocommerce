<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Common;

interface FactoryInterface
{
    /**
     * Создаёт экземпляр класса с необходимыми зависимостями
     *
     * @return object
     */
    public function create(): object;
}
