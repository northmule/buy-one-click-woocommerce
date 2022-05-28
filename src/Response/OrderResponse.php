<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Response;

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
}
