<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Templates;

use Coderun\BuyOneClick\SimpleDataObjects\DataTransferObject;
use Coderun\BuyOneClick\SimpleDataObjects\FieldsOfOrderForm;

interface TemplateInterface
{
    /**
     * Создаёт html форму
     *
     * @param FieldsOfOrderForm $params
     *
     * @return string
     */
    public function render(DataTransferObject $params): string;
}
