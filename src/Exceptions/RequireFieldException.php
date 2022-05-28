<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Exceptions;

use function sprintf;

/**
 * Class RequireFieldException
 *
 * @package Coderun\BuyOneClick\Exceptions
 */
class RequireFieldException extends BaseException implements ExceptionInterface
{
    /**
     * @param string $fieldName
     *
     * @return RequireFieldException
     */
    public static function fieldIsRequired(string $fieldName): RequireFieldException
    {
        return new self(
            sprintf(
                __('The %s field is required', 'coderun-oneclickwoo'),
                $fieldName
            ),
            self::CODE_SUCCESS
        );
    }
}
