<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Service\Factory;

use Coderun\BuyOneClick\Common\FactoryInterface;
use Coderun\BuyOneClick\Core;
use Coderun\BuyOneClick\Service\ShortCodes;

/**
 * Class ButtonFactory
 */
class ShortCodesFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     *
     * @return ShortCodes
     */
    public function create(): ShortCodes
    {
        return new ShortCodes(Core::getInstance()->getCommonOptions());
    }
}
