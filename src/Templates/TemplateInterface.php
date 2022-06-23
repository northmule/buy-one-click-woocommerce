<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Templates;

interface TemplateInterface
{
    
    /**
     * Создаёт html форму
     *
     * @param $params
     *
     * @return string
     */
    public function render($params): string;
}