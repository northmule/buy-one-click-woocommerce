<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Templates\Elements;

interface ElementInterface
{
    
    /**
     * Создаёт html элемент
     *
     * @return string
     */
    public function render(): string;
}