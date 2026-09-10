<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Constant\Options;

/**
 * Class ActionsForm
 */
class ActionsForm
{
    /**
     * Действие: Ни чего не делать после отправки
     */
    public const string NOTHING_TO_DO = '1';
    /**
     * Действие: Закрыть форму через
     */
    public const string CLOSE_FORM_VIA = '2';
    /**
     * Действие: Показать доп сообщение
     */
    public const string SHOW_MESSAGE = '3';
    /**
     * Действие: Перенаправить на страницу
     */
    public const string REDIRECT_TO_PAGE = '4';
    /**
     * Действие: Направить на страницу заказа WooCommerce
     */
    public const string SEND_TO_ORDER_PAGE = '5';
    /**
     * Действие: Направить на страницу оплаты WooCommerce
     */
    public const string SEND_TO_ORDER_PAYMENT_PAGE = '6';
}
