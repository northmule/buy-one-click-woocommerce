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
     * Настройки плагина
     * @var type
     */
    protected $options_plugin = [];

    /**
     * Использование дополнение вариаций
     */
    public $module_variation = false;

    public function isset_woo_order($orderId)
    {
        $order = \wc_get_order($orderId);
        if (!$order instanceof \WC_Order) {
            return false;
        }
        if (empty($order->get_id())) {
            return false;
        }
        if ($order->get_status()==='trash') {
            return false;
        }
        return true;
    }

    /**
     * Вернут настройки плагина
     * @deprecated
     * @return array
     */
    public function get_options($name = null)
    {
        if ($name === null) {
            return $this->options_plugin;
        } else {
            return $this->options_plugin[$name];
        }
    }

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

        // todo - убрать
        $this->options_plugin = array(
            'buyoptions' => \get_option('buyoptions', []),
            'buynotification' => \get_option('buynotification', [])
        );
    }
}
