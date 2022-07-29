<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Controller\Factory;

use Coderun\BuyOneClick\Common\FactoryInterface;
use Coderun\BuyOneClick\Core;
use Coderun\BuyOneClick\Controller\AdminController;

/**
 * Class AdminControllerFactory
 */
class AdminControllerFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     *
     * @return AdminController
     */
    public function create(): AdminController
    {
        return new AdminController(
            Core::getInstance()->getCommonOptions(),
            Core::getInstance()->getNotificationOptions()
        );
    }
}
