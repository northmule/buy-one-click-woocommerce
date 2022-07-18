<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Response;

/**
 * Class ErrorResponse
 *
 * @package Coderun\BuyOneClick\Response
 */
class ErrorResponse implements ResponseInterface
{
    /**
     * @var string
     */
    protected string $message = '';

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
     * @return ErrorResponse
     */
    public function setMessage(string $message): ErrorResponse
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return $this->message !== '';
    }
}
