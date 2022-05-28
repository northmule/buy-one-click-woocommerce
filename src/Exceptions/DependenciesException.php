<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Exceptions;

use function sprintf;

/**
 * Class DependenciesException
 *
 * @package Coderun\BuyOneClick\Exceptions
 */
class DependenciesException extends BaseException implements ExceptionInterface
{
    /**
     * @param string $message
     *
     * @return DependenciesException
     */
    public static function captchaVerificationPluginError(string $message): DependenciesException
    {
        return new self(
            sprintf('captcha verification plugin error: %s', $message)
        );
    }

    /**
     * @return DependenciesException
     */
    public static function orderCreationErrorWoo(): DependenciesException
    {
        return new self(__('Couldn\'t create WooCommerce order', 'coderun-oneclickwoo'));
    }
}
