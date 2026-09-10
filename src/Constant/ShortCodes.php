<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Constant;

/**
 * Class ShortCodes
 */
class ShortCodes
{
    public const string VIEW_BUY_BUTTON = 'viewBuyButton';
    public const string VIEW_BUY_BUTTON_CUSTOM = 'viewBuyButtonCustom';

    /**
     * @return array<int, string>
     */
    public static function all(): array
    {
        return [
            self::VIEW_BUY_BUTTON,
            self::VIEW_BUY_BUTTON_CUSTOM,
        ];
    }
}
