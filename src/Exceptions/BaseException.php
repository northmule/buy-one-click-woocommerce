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
    protected const int CODE_SUCCESS = 200;
}
