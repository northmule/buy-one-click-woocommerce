<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\ValueObject;

use Coderun\BuyOneClick\Options\Notification as NotificationOptions;

/**
 * Болванка формы заказа, для восстановления в истории заказов
 *
 * Class OrderBlank
 *
 * @package Coderun\BuyOneClick\ValueObject
 */
class OrderBlank extends OrderForm
{
    public function __construct()
    {
    }
}
