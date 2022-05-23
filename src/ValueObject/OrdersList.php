<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\ValueObject;

/**
 * Class OrdersList
 *
 * @package Coderun\BuyOneClick\ValueObject
 */
class OrdersList
{
    
    /**
     * Время заказа
     *
     * @var string
     */
    protected string $dateTime;
    
    /**
     * Внутренний номер заказа
     *
     * @var string
     */
    protected string $itemNumber;
    
    /**
     * Информация о клиенте
     *
     * @var string
     */
    protected string $customer;
    
    /**
     * Телефон
     *
     * @var string
     */
    protected string $phone;
    
    /**
     * Email
     *
     * @var string
     */
    protected string $email;
    
    /**
     * Информация о товаре
     *
     * @var string
     */
    protected string $productInformation;
    
    /**
     * Цена
     *
     * @var string
     */
    protected string $price;
    
    /**
     * Комментарий покупателя
     *
     * @var string
     */
    protected string $message;
    
    /**
     * Ссылка на товар
     *
     * @var string
     */
    protected string $productUrl;
    
    /**
     * Лог смс шлюза
     *
     * @var string
     */
    protected string $smsLog;
    
    /**
     * Локальный статус заказа
     *
     * @var string
     */
    protected string $orderStatus;
    
    public function __construct(array $orders)
    {
    }
    
    
    
    
}