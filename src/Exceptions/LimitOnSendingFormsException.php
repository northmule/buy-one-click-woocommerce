<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Exceptions;

/**
 * Class LimitOnSendingFormsException
 *
 * @package Coderun\BuyOneClick\Exceptions
 */
class LimitOnSendingFormsException extends BaseException implements ExceptionInterface
{
    /**
     * @param string|null $message
     *
     * @return LimitOnSendingFormsException
     */
    public static function error(?string $message = null): LimitOnSendingFormsException
    {
        if ($message == null) {
            $message = __('You have already sent an order!', 'coderun-oneclickwoo');
        }
        return new self(
            $message,
            self::CODE_SUCCESS
        );
    }
}
