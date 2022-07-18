<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\SimpleDataObjects;

/**
 * Форма для оформления заказа
 *
 * Class FieldsOfOrderForm
 *
 * @package Coderun\BuyOneClick\SimpleDataObjects
 */
class FieldsOfOrderForm extends DataTransferObject
{
    /**
     * Ид товара или вариации
     *
     * @var integer
     */
    public int $productId;
    /**
     * Имя товара
     *
     * @var string
     */
    public string $productName;
    /**
     * Цена
     *
     * @var float
     */
    public float $productPrice;
    /**
     * Форма вызвана через шорткод
     *
     * @var boolean
     */
    public bool $shortCode;
    /**
     * URL ан изображение
     *
     * @var string
     */
    public string $productImg;
    /**
     * Количество товаров
     *
     * @var integer
     */
    public int $productCount = 1;
    /**
     * HTML ссылка на изображение
     *
     * @var string
     */
    public string $productSrcImg;
    /**
     * Есть дополнение для вариаций
     *
     * @var boolean
     */
    public bool $variationPlugin;
    /**
     * Часть настройки, встраивание css в форму
     *
     * @var boolean
     */
    public bool $templateStyle;
    /**
     * HTML формы для загрузки файлов
     *
     * @var string
     */
    public string $formWithFiles;
    /**
     * HTML для указания количества
     *
     * @var string
     */
    public string $formWithQuantity;
}
