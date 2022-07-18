<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Service\Sms\Factory;

use Coderun\BuyOneClick\Common\FactoryInterface;
use Coderun\BuyOneClick\Core;
use Coderun\BuyOneClick\Service\Sms\SmsCenter;

/**
 * Class SmsCenterFactory
 */
class SmsCenterFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     *
     * @return SmsCenter
     */
    public function create(): SmsCenter
    {
        return new SmsCenter(Core::getInstance()->getNotificationOptions());
    }
}
