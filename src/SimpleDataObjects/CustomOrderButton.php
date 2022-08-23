<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\SimpleDataObjects;

/**
 * Кнопка "Заказать" для шорткода
 *
 * Class CustomOrderButton
 *
 * @package Coderun\BuyOneClick\SimpleDataObjects
 */
class CustomOrderButton extends DataTransferObject
{
    /**
     * Ид товара
     *
     * @var integer
     */
    public int $productId;
    /**
     * Цена товара
     *
     * @var string
     */
    public string $productPrice;
    /**
     * Количество товара
     *
     * @var string
     */
    public string $productCount;
    /**
     * Имя товара
     *
     * @var string
     */
    public string $productName;
    /**
     * Имя кнопки
     *
     * @var string
     */
    public string $buttonName;
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
    /**
     * Цена для отображения
     *
     * @var string
     */
    public string $productPriceHtml;
}
