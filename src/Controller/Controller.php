<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Controller;

use Coderun\BuyOneClick\Common\Logger;
use Coderun\BuyOneClick\Options\General as GeneralOptions;
use Coderun\BuyOneClick\Options\Notification as NotificationOptions;
use WC_Logger;

/**
 * Class RequestController
 */
abstract class Controller implements ControllerInterface
{
    public const string REQUEST_KEY = 'coderun_send_form_buy_one_click';

    /**
     * Action front-end nonce-a
     */
    public const string FRONTEND_NONCE_ACTION = 'buy_one_click_frontend';

    /**
     * @var Logger
     */
    protected Logger $logger;
    /**
     * Настройки плагина
     *
     * @var GeneralOptions
     */
    protected GeneralOptions $commonOptions;
    /**
     * Настройки плагина
     *
     * @var NotificationOptions
     */
    protected NotificationOptions $notificationOptions;

    public function __construct(GeneralOptions $commonOptions, NotificationOptions $notificationOptions)
    {
        $this->logger = Logger::getInstance();
        $this->commonOptions = $commonOptions;
        $this->notificationOptions = $notificationOptions;
    }

    /**
     * Возвращает значение nonce фронтенд-запроса
     *
     * @return string
     */
    protected function getFrontendNonce(): string
    {
        // Frontend nonce extraction; verified by callers via wp_verify_nonce(FRONTEND_NONCE_ACTION).
        return isset($_POST['booc_nonce']) ? sanitize_text_field(wp_unslash($_POST['booc_nonce'])) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
    }

    /**
     * Прерывает обработку запроса при ошибке проверки nonce
     *
     * @return void
     */
    protected function abortOnFailedNonce(): void
    {
        wp_die('-1');
    }
}
