<?php

namespace Coderun\BuyOneClick;


class Products {

    protected static $_instance = null;

    /**
     * Singletone
     * @return Products
     */
    public static function getInstance() {

        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    

    protected function __construct() {
        
    }

    public function __clone() {
        throw new \Exception('Forbiden instance __clone');
    }

    public function __wakeup() {
        throw new \Exception('Forbiden instance __wakeup');
    }

}
