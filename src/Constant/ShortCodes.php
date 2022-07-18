<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Constant;

/**
 * Class ShortCodes
 */
class ShortCodes
{
    /**
     * @var string
     */
    public const VIEW_BUY_BUTTON = 'viewBuyButton';
    /**
     * @var string
     */
    public const VIEW_BUY_BUTTON_CUSTOM = 'viewBuyButtonCustom';

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
