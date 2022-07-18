<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Common;

use WC_Logger;

use function wc_get_logger;
use function sprintf;

class Logger
{
    /**
     * @var string
     */
    protected const PREFIX = 'Buy one click WooCommerce plugin';

    /**
     * @var ?WC_Logger
     */
    private ?WC_Logger $logger = null;
    /**
     * @var Logger|null
     */
    private static ?Logger $instance = null;

    protected function __construct()
    {
        $this->logger = wc_get_logger();
    }

    /**
     * @return Logger
     */
    public static function getInstance(): Logger
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Добавить сообщение в лог
     *
     * @param string     $message
     * @param array|null $context
     *
     * @return void
     */
    public function info(string $message, ?array $context = null)
    {
        $this->logger->info(sprintf('%s: %s', self::PREFIX, $message), $context);
    }

    /**
     * Добавить сообщение в лог
     *
     * @param string     $message
     * @param array|null $context
     *
     * @return void
     */
    public function error(string $message, ?array $context = null)
    {
        $this->logger->error(sprintf('%s: %s', self::PREFIX, $message), $context);
    }
}
