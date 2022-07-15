<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Controller;

use Coderun\BuyOneClick\Common\Logger;
use Coderun\BuyOneClick\Options\General as GeneralOptions;
use WC_Logger;

/**
 * Class RequestController
 */
abstract class Controller implements ControllerInterface
{
    /** @var string  */
    public const REQUEST_KEY = 'coderun_send_form_buy_one_click';

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
     *
     */
    public function __construct(GeneralOptions $commonOptions)
    {
        $this->logger = Logger::getInstance();
        $this->commonOptions = $commonOptions;
    }
}
