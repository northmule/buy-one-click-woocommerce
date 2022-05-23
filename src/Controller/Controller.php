<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Controller;

use Coderun\BuyOneClick\Logger;

/**
 * Class RequestController
 */
abstract class Controller implements ControllerInterface
{
    /** @var string  */
    const REQUEST_KEY = 'coderun_send_form_buy_one_click';
    
    /**
     * @var Logger
     */
    protected Logger $logger;
    
    public function __construct()
    {
        $this->logger = Logger::getInstance();
    }
    
    
}