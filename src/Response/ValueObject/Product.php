<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Response\ValueObject;

use Coderun\BuyOneClick\ValueObject\OrderForm;
use WC_Product;

use WC_Product_Variation;

use function is_array;
use function implode;

/**
 * Class Product
 */
class Product
{
    /**
     * ИД товара или вариации
     *
     * @var int
     */
    protected int $id = 0;

    /**
     *  Название
     *
     * @var string
     */
    protected string $name = '';

    /**
     * Артикул
     *
     * @var string
     */
    protected string $sku = '';

    /**
     * Количестов
     *
     * @var int
     */
    protected int $quantity = 1;

    /**
     * Цена за еденицу
     *
     * @var float
     */
    protected float $price = 0.00;

    /**
     * Категория
     *
     * @var string
     */
    protected string $category = '';

    /**
     * Разновидность товара
     *
     * @var string
     */
    protected string $variant = '';

    /**
     * Constructor
     *
     * @param OrderForm $orderForm
     */
    public function __construct(OrderForm $orderForm)
    {
        $this->id = $orderForm->getProductId();
        $this->name = $orderForm->getProductName();
        $this->sku = $this->findProductSku($orderForm->getProductId());
        $this->quantity = $orderForm->getQuantityProduct();
        $this->price = $orderForm->getProductPrice();
        $this->category = $this->findProductCategories($orderForm->getProductId());
        $this->variant = $this->findProductVariant($orderForm->getProductId());
    }

    /**
     * Артикул товара
     *
     * @param int $productId
     *
     * @return string
     */
    protected function findProductSku(int $productId): string
    {
        $product = new WC_Product($productId);
        return $product->get_sku();
    }

    /**
     * Категории товара
     *
     * @param int $productId
     *
     * @return string
     */
    protected function findProductCategories(int $productId): string
    {
        $categories = get_the_terms($productId, 'product_cat');
        if (!is_array($categories)) {
            return '';
        }
        $result = [];
        foreach ($categories as $category) {
            $result[] = $category->name;
        }

        return implode(',', $result);
    }

    /**
     * Вариант товара
     *
     * @param int $productId
     *
     * @return string
     */
    protected function findProductVariant(int $productId): string
    {
        $product = new WC_Product($productId);
        if (!$product->is_type('variable')) {
            return '';
        }
        $variation = new WC_Product_Variation($product->get_id());
        return $variation->get_name();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }
}
