<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\SimpleDataObjects;

/**
 * Кнопка "Заказать"
 *
 * Class OrderButton
 *
 * @package Coderun\BuyOneClick\SimpleDataObjects
 */
class OrderButton extends DataTransferObject
{
    
    /**
     * Ид товара
     *
     * @var int
     */
    public int $productId;
    
    /**
     * Имя кнопки
     *
     * @var string
     */
    public string $buttonName;
    
    /**
     * Ид вариации
     *
     * @var int
     */
    public int $variationId;
    
    /**
     * Css
     *
     * @var string
     */
    public string $inlineStyle;
    
    /**
     * Js
     *
     * @var string
     */
    public string $inlineScript;
    
    
}