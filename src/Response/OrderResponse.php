<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Response;

use Coderun\BuyOneClick\Response\ValueObject\Product;

/**
 * Ответ при совершении заказа
 *
 * Class OrderResponse
 *
 * @package Coderun\BuyOneClick\Response
 */
class OrderResponse implements ResponseInterface
{
    /** @var string  */
    protected string $message = '';
    /** @var string  */
    protected string $result = '';
    /** @var string  */
    protected string $redirectUrl = '';
    /** @var array<int, Product> */
    protected array $products = [];
    /** @var string  */
    protected string $orderUuid = '';

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return OrderResponse
     */
    public function setMessage(string $message): OrderResponse
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getResult(): string
    {
        return $this->result;
    }

    /**
     * @param string $result
     *
     * @return OrderResponse
     */
    public function setResult(string $result): OrderResponse
    {
        $this->result = $result;
        return $this;
    }

    /**
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }

    /**
     * @param string $redirectUrl
     *
     * @return OrderResponse
     */
    public function setRedirectUrl(string $redirectUrl): OrderResponse
    {
        $this->redirectUrl = $redirectUrl;
        return $this;
    }

    /**
     * @return Product[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @param Product[] $products
     *
     * @return OrderResponse
     */
    public function setProducts(array $products): OrderResponse
    {
        $this->products = $products;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderUuid(): string
    {
        return $this->orderUuid;
    }

    /**
     * @param string $orderUuid
     *
     * @return OrderResponse
     */
    public function setOrderUuid(string $orderUuid): OrderResponse
    {
        $this->orderUuid = $orderUuid;
        return $this;
    }
}
