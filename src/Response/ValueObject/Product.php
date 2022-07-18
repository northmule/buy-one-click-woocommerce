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
     * @var integer
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
     * @var integer
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
        if ($orderForm->isWooCommerceProduct()) {
            $this->sku = $this->findProductSku($orderForm->getProductId());
        }
        $this->quantity = $orderForm->getQuantityProduct();
        $this->price = $orderForm->getProductPrice();
        if ($orderForm->isWooCommerceProduct()) {
            $this->category = $this->findProductCategories($orderForm->getProductId());
        }
        if ($orderForm->isWooCommerceProduct()) {
            $this->variant = $this->findProductVariant($orderForm->getProductId());
        }
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
        $product = wc_get_product($productId);
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
        $product = wc_get_product($productId);
        $categoryNames = [];
        foreach ($product->get_category_ids() as $categoryId) {
            $category = get_term($categoryId, 'product_cat');
            if ($category && !is_wp_error($category)) {
                $categoryNames[] = $category->name;
            }
        }
        return implode('/', $categoryNames);
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
        $product = wc_get_product($productId);
        if (!$product instanceof WC_Product_Variation) {
            return '';
        }
        return $product->get_name();
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
