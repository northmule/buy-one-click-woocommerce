<?php

namespace Coderun\BuyOneClick;

/**
 * Description of Help
 *
 * @author djo
 */
class Help
{
    protected static $_instance = null;


    /**
     * Использование дополнение вариаций
     */
    public $module_variation = false;
    
    /**
     * Singletone
     * @return Help
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    protected function __construct()
    {

    }
}
