<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Utils;

use function sprintf;
use function substr;
use function hexdec;

/**
 * Class Uuid
 *
 * @package Coderun\BuyOneClick\Utils
 */
class Uuid
{
    /**
     * @return string
     * @throws \Exception
     */
    public static function uuidGenerator(): string
    {
        $uuid = bin2hex(random_bytes(16));

        return sprintf(
            '%08s-%04s-4%03s-%04x-%012s',
            substr($uuid, 0, 8),
            substr($uuid, 8, 4),
            substr($uuid, 13, 3),
            hexdec(substr($uuid, 16, 4)) & 0x3fff | 0x8000,
            substr($uuid, 20, 12)
        );
    }
}
