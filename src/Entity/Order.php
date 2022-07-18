<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Entity;

use DateTime;

/**
 * Заказ плагина из таблицы wp_coderun_oneclickwoo_orders
 *
 * Class Order
 */
class Order implements EntityInterface
{
    /**
     * @var integer
     */
    protected int $id;
    /**
     * Версия плагина
     *
     * @var string|null
     */
    protected ?string $pluginVersion;
    /**
     * Активность
     *
     * @var boolean
     */
    protected bool $active;
    /**
     * Статус заказа
     *
     * @var integer
     */
    protected int $status;
    /**
     * ИД товара WooCommerce
     *
     * @var integer|null
     */
    protected ?int $productId;
    /**
     * Имя товара WooCommerce
     *
     * @var string
     */
    protected ?string $productName;
    /**
     * ХЗ
     *
     * @var string
     */
    protected ?string $productMeta;
    /**
     * Цена товара WooCommerce
     *
     * @var float
     */
    protected ?float $productPrice;
    /**
     * Заказанное количество
     *
     * @var integer
     */
    protected int $productQuantity;
    /**
     * Вся форма заказа
     *
     * @var string|null
     */
    protected ?string $form;
    /**
     * СМС лог от сервиса СМС
     *
     * @var string|null
     */
    protected ?string $smsLog;
    /**
     * ID Заказ WooCommerce
     *
     * @var integer|null
     */
    protected ?int $wooOrderId;
    /**
     * Покупатель
     *
     * @var integer
     */
    protected ?int $userId;
    /**
     * @var DateTime|null
     */
    protected ?DateTime $dateUpdate;
    /**
     * @var DateTime
     */
    protected DateTime $dateCreate;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Order
     */
    public function setId(int $id): Order
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPluginVersion(): ?string
    {
        return $this->pluginVersion;
    }

    /**
     * @param string|null $pluginVersion
     *
     * @return Order
     */
    public function setPluginVersion(?string $pluginVersion): Order
    {
        $this->pluginVersion = $pluginVersion;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     *
     * @return Order
     */
    public function setActive(bool $active): Order
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return Order
     */
    public function setStatus(int $status): Order
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getProductId(): ?int
    {
        return $this->productId;
    }

    /**
     * @param int|null $productId
     *
     * @return Order
     */
    public function setProductId(?int $productId): Order
    {
        $this->productId = $productId;
        return $this;
    }

    /**
     * @return string
     */
    public function getProductName(): ?string
    {
        return $this->productName;
    }

    /**
     * @param string $productName
     *
     * @return Order
     */
    public function setProductName(?string $productName): Order
    {
        $this->productName = $productName;
        return $this;
    }

    /**
     * @return string
     */
    public function getProductMeta(): ?string
    {
        return $this->productMeta;
    }

    /**
     * @param string $productMeta
     *
     * @return Order
     */
    public function setProductMeta(?string $productMeta): Order
    {
        $this->productMeta = $productMeta;
        return $this;
    }

    /**
     * @return float
     */
    public function getProductPrice(): ?float
    {
        return $this->productPrice;
    }

    /**
     * @param float $productPrice
     *
     * @return Order
     */
    public function setProductPrice(?float $productPrice): Order
    {
        $this->productPrice = $productPrice;
        return $this;
    }

    /**
     * @return int
     */
    public function getProductQuantity(): int
    {
        return $this->productQuantity;
    }

    /**
     * @param int $productQuantity
     *
     * @return Order
     */
    public function setProductQuantity(int $productQuantity): Order
    {
        $this->productQuantity = $productQuantity;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getForm(): ?string
    {
        return $this->form;
    }

    /**
     * @param string|null $form
     *
     * @return Order
     */
    public function setForm(?string $form): Order
    {
        $this->form = $form;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSmsLog(): ?string
    {
        return $this->smsLog;
    }

    /**
     * @param string|null $smsLog
     *
     * @return Order
     */
    public function setSmsLog(?string $smsLog): Order
    {
        $this->smsLog = $smsLog;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getWooOrderId(): ?int
    {
        return $this->wooOrderId;
    }

    /**
     * @param int|null $wooOrderId
     *
     * @return Order
     */
    public function setWooOrderId(?int $wooOrderId): Order
    {
        $this->wooOrderId = $wooOrderId;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     *
     * @return Order
     */
    public function setUserId(?int $userId): Order
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDateUpdate(): ?DateTime
    {
        return $this->dateUpdate;
    }

    /**
     * @param DateTime|null $dateUpdate
     *
     * @return Order
     */
    public function setDateUpdate(?DateTime $dateUpdate): Order
    {
        $this->dateUpdate = $dateUpdate;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateCreate(): DateTime
    {
        return $this->dateCreate;
    }

    /**
     * @param DateTime $dateCreate
     *
     * @return Order
     */
    public function setDateCreate(DateTime $dateCreate): Order
    {
        $this->dateCreate = $dateCreate;
        return $this;
    }
}
