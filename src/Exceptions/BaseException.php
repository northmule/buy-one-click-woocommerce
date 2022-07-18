<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Exceptions;

use RuntimeException;

/**
 * Class BaseException
 *
 * @package Coderun\BuyOneClick\Exceptions
 */
class BaseException extends RuntimeException
{
    /**
     * @var int
     */
    protected const CODE_SUCCESS = 200;
}
