<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Templates\Elements\Factory;

use Coderun\BuyOneClick\Common\FactoryInterface;
use Coderun\BuyOneClick\Core;
use Coderun\BuyOneClick\Templates\Elements\Files as ElementFiles;

/**
 * Class QuantityFactory
 */
class FilesFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     *
     * @return ElementFiles
     */
    public function create(): ElementFiles
    {
        return new ElementFiles(Core::getInstance()->getCommonOptions());
    }
}
