<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Templates\Elements\Factory;

use Coderun\BuyOneClick\Common\FactoryInterface;
use Coderun\BuyOneClick\Core;
use Coderun\BuyOneClick\Templates\Elements\Quantity as ElementQuantity;

/**
 * Class QuantityFactory
 */
class QuantityFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     *
     * @return ElementQuantity
     */
    public function create(): ElementQuantity
    {
        return new ElementQuantity(Core::getInstance()->getCommonOptions());
    }
}
