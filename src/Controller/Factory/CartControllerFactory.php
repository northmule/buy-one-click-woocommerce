<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Controller\Factory;

use Coderun\BuyOneClick\Common\FactoryInterface;
use Coderun\BuyOneClick\Core;
use Coderun\BuyOneClick\Controller\CartController;

/**
 * Class CartControllerFactory
 */
class CartControllerFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     *
     * @return CartController
     */
    public function create(): CartController
    {
        return new CartController(
            Core::getInstance()->getCommonOptions(),
            Core::getInstance()->getNotificationOptions()
        );
    }
}
