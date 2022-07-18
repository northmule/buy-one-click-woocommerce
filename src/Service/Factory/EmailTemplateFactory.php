<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Service\Factory;

use Coderun\BuyOneClick\Common\FactoryInterface;
use Coderun\BuyOneClick\Core;
use Coderun\BuyOneClick\Repository\Order as OrderRepository;
use Coderun\BuyOneClick\Service\EmailTemplate;

/**
 * Class EmailTemplateFactory
 */
class EmailTemplateFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     *
     * @return EmailTemplate
     */
    public function create(): EmailTemplate
    {
        return new EmailTemplate(
            OrderRepository::getInstance(),
            Core::getInstance()->getNotificationOptions(),
        );
    }
}
