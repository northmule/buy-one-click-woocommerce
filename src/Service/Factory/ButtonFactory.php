<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Service\Factory;

use Coderun\BuyOneClick\Common\FactoryInterface;
use Coderun\BuyOneClick\Core;
use Coderun\BuyOneClick\Service\Button;

/**
 * Class ButtonFactory
 */
class ButtonFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     *
     * @return Button
     */
    public function create(): Button
    {
        return new Button(Core::getInstance()->getCommonOptions());
    }
}
