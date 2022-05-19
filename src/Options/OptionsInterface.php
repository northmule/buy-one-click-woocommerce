<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Options;

interface OptionsInterface
{
    
    /**
     * Все поля в ассоциативный массив
     *
     * @return array
     */
    public function toArray(): array;
    
    /**
     * Все поля в ассоциативный массив настроек WordPress
     *
     * @return array
     */
    public function toArrayWp(): array;
}