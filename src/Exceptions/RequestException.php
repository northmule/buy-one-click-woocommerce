<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Exceptions;

/**
 * Class RequestException
 *
 * @package Coderun\BuyOneClick\Exceptions
 */
class RequestException extends BaseException implements ExceptionInterface
{
    /**
     * @return RequestException
     */
    public static function emptyRequest(): RequestException
    {
        return new self('Empty request');
    }

    /**
     * @return RequestException
     */
    public static function nonceError(): RequestException
    {
        return new self('the nonce field data is incorrect');
    }
}
