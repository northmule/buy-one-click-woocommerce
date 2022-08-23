<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Templates\Elements;

use Coderun\BuyOneClick\SimpleDataObjects\DataTransferObject;

interface ElementInterface
{
    /**
     * Создаёт html элемент
     *
     * @return string
     */
    public function render(DataTransferObject $params): string;
}
