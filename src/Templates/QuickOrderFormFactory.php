<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Templates;

use Coderun\BuyOneClick\Common\FactoryInterface;
use Coderun\BuyOneClick\Core;

/**
 * Class QuickOrderFormFactory
 *
 * @package Coderun\BuyOneClick\Templates\Elements\Factory
 */
class QuickOrderFormFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     *
     * @return QuickOrderForm
     */
    public function create(): QuickOrderForm
    {
        return new QuickOrderForm(Core::getInstance()->getCommonOptions());
    }
}
