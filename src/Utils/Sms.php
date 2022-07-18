<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Utils;

use Coderun\BuyOneClick\ValueObject\OrderForm;

use function strtr;

/**
 * Class Sms
 *
 * @package Coderun\BuyOneClick\Utils
 */
class Sms
{
    /**
     * Собирает тело сообщения SMS
     *
     * @param string    $options   Текст смс
     *                             сообщения
     * @param OrderForm $orderForm
     *
     * @return string
     */
    public static function composeSms(string $options, OrderForm $orderForm)
    {
        return strtr(
            $options,
            [
                '%FIO%'     => $orderForm->getUserName(),
                '%FON%'     => $orderForm->getUserPhone(),
                '%EMAIL%'   => $orderForm->getUserEmail(),
                '%DOPINFO%' => $orderForm->getOrderAdminComment(),
                '%TPRICE%'  => $orderForm->getProductPrice(),
                '%TNAME%'   => $orderForm->getProductName(),
            ]
        );
    }
}
