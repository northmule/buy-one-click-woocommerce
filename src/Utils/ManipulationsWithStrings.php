<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Utils;

use function explode;
use function implode;
use function ucfirst;

/**
 * Class ManipulationsWithStrings
 */
class ManipulationsWithStrings
{
    /**
     * Изменение имени с-ва
     *
     * @param string $propertyNameSnakeCase
     *
     * @return string
     */
    public static function snakeInCamelCase(string $propertyNameSnakeCase): string
    {
        $partsProperty = explode('_', $propertyNameSnakeCase);
        if (!isset($partsProperty[1])) {
            return $propertyNameSnakeCase;
        }
        $camelCaseParts = [];
        foreach ($partsProperty as $key => $part) {
            if ($key == 0) {
                $camelCaseParts[] = $part;
                continue;
            }
            $camelCaseParts[] = ucfirst($part);
        }
        return implode('', $camelCaseParts);
    }
}
