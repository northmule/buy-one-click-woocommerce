<?php

namespace Coderun\BuyOneClick;

if (!defined('ABSPATH')) {
    exit;
}

use Coderun\BuyOneClick\Help;
use Coderun\BuyOneClick\Core;

class ShortCodes {
    
    protected $options = array();
    
    protected static $_instance = null;
    
    /**
     * Singletone
     * @return self
     */
    public static function getInstance() {
        
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * Кнопка купить
     * Возможно размещение только в цикле вывода товаров
     */
    protected function __construct() {
        
        $this->options = Help::getInstance()->get_options();
        
        if (!shortcode_exists('viewBuyButton')) {
            add_shortcode('viewBuyButton', array($this, 'viewBuyButton'));
        }
        if (!shortcode_exists('viewBuyButtonCustom')) {
            add_shortcode('viewBuyButtonCustom', array($this, 'viewBuyButtonCustom'));
        }
    }
    
    /**
     * Класическая кнопка покупки в один клик
     * Используется везде где можно получить ИД товара из Объекта WP
     * @param array $params - Дополнительные параметры шорткода. $params[id] - ИД товара WooCommerce
     * @return string
     */
    public function viewBuyButton($params) {
        $buyoptions = $this->options['buyoptions'];
        $content = '';
        if (!empty($buyoptions['enable_button_shortcod']) and $buyoptions['enable_button_shortcod'] === 'on') {
            $params = \shortcode_atts(['id' => 0], $params);
            $core = Core::getInstance();
            $core->styleAddFrontPage();
            $core->scriptAddFrontPage();
            if (Help::getInstance()->module_variation) {
                $content = \Coderun\BuyOneClick\VariationsAddition::getInstance()->shortCode();
            }
            $content .= BuyFunction::viewBuyButton(true, $params);
            return $content;
        } else {
            return '';
        }
    }
    
    /**
     * @param array $arParams id - код товара, name- наименование, count-количество,price- цена(число)
     * Кнопка с возможностью передать параметры
     */
    public function viewBuyButtonCustom($arParams) {
        $buyoptions = $this->options['buyoptions'];
        if (!empty($buyoptions['enable_button_shortcod']) and $buyoptions['enable_button_shortcod'] === 'on') {
            $arParams = \shortcode_atts(array(
                'id' => '1',
                'name' => 'noname',
                'count' => 1,
                'price' => 5,
            ), $arParams);
            $core = Core::getInstance();
            $core->styleAddFrontPage();
            $core->scriptAddFrontPage();
            return BuyFunction::viewBuyButtonCustrom($arParams);
        } else {
            return '';
        }
    }
    
}
