<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Controller\Factory;

use Coderun\BuyOneClick\Common\FactoryInterface;
use Coderun\BuyOneClick\Core;
use Coderun\BuyOneClick\Controller\FormController;

/**
 * Class FormControllerFactory
 */
class FormControllerFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     *
     * @return FormController
     */
    public function create(): FormController
    {
        return new FormController(
            Core::getInstance()->getCommonOptions(),
            Core::getInstance()->getNotificationOptions()
        );
    }
}
