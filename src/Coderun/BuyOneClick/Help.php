<?php

namespace Coderun\BuyOneClick;


/**
 * Description of Help
 *
 * @author djo
 */
class Help {

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

    /**
     * Проверяют указанное поле и возвращает значение если есть
     * @param array $data
     * @param string $key ключ массива
     * @return array|mixed|string
     */
    public function get_value_field($data, $key) {

        $result = '';

        if (is_array($data)) {
            if (isset($data[$key])) {
                if (!is_array($data[$key])) {
                    $result = wp_specialchars_decode(esc_html($data[$key]), ENT_QUOTES);
                } else {
                    $result = $data[$key];
                }
            }
        } else {
            $result = $data;
        }

        return $result;
    }
    
    public function isset_woo_order($orderId) {
        $order = \wc_get_order($orderId);
        if(!$order instanceof \WC_Order) {
            return false;
        }
        if(empty($order->get_id())) {
           return false;
        }
        if($order->get_status()==='trash'){
            return false;
        }
        return true;
    }

    /**
     * Для совместимости данных формы в виде массива name -> value
     */
    public function get_form_data_legacy($form) {
        $result = array();

        if (!is_array($form)) {
            return $result;
        }
        $count = 0;
        foreach ($form as $key => $value) {
            $result[$count]['name'] = $key;
            $result[$count]['value'] = $value;
            $count++;
        }
        return $result;
    }

    /**
     * Вернут настройки плагина
     * @deprecated
     * @return array
     */
    public function get_options($name = null) {
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
    public static function getInstance() {

        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Список файлов для сообщения
     * @param type $files
     * @return array Description
     */
    public function get_message_files($files) {
        $result = array();
        foreach ($files as $file) {
            $result[] = $file['url'];
        }
        return $result;
    }

    /**
     * Файлы в виде ссылок и строки
     * @param type $files
     * @return string
     */
    public function get_message_files_url($files) {
        $result = '';

        $name = __('File', 'coderun-oneclickwoo');

        $count = 1;

        foreach ($this->get_message_files($files) as $url) {
            if (empty(trim($url))) {
                continue;
            }
            $result .= "<br><a href='{$url}'>{$name} {$count}</a>";

            $count++;
        }

        return $result;
    }

    protected function __construct() {

        // todo - убрать
        $this->options_plugin = array(
            'buyoptions' => \get_option('buyoptions', []),
            'buynotification' => \get_option('buynotification', [])
        );
    }

    public function __clone() {
        throw new \Exception('Forbiden instance __clone');
    }

    public function __wakeup() {
        throw new \Exception('Forbiden instance __wakeup');
    }

}
