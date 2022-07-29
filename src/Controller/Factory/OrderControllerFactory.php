<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Controller\Factory;

use Coderun\BuyOneClick\Common\FactoryInterface;
use Coderun\BuyOneClick\Core;
use Coderun\BuyOneClick\Controller\OrderController;

/**
 * Class OrderControllerFactory
 */
class OrderControllerFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     *
     * @return OrderController
     */
    public function create(): OrderController
    {
        return new OrderController(
            Core::getInstance()->getCommonOptions(),
            Core::getInstance()->getNotificationOptions()
        );
    }
}
