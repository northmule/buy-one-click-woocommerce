<?php

namespace Coderun\BuyOneClick;

use BuySMSC;
use Exception;

/**
 * Класс для работы с JavaScript функциями отправляемыми через скрипты
 */
class Ajax {
    
    protected $logger = null;
    
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
     * Конструктор класса
     */
    protected function __construct() {
        $this->logger = Logger::getInstance();
        $this->initAction();
    }
    
    /**
     * Адды
     */
    public function initAction() {
        
        $pref = 'coderun_send_form_buy_one_click';
        
        add_action("wp_ajax_{$pref}_buybuttonform", array($this, 'ajaxBuyButtonForm'));
        add_action("wp_ajax_nopriv_{$pref}_buybuttonform", array($this, 'ajaxBuyButtonForm'));
        add_action('wp_ajax_removeorder', array($this, 'ajaxRemoveOrderId'));
        add_action('wp_ajax_updatestatus', array($this, 'ajaxStatusOrderId'));
        add_action('wp_ajax_nopriv_updatestatus', array($this, 'ajaxStatusOrderId'));
        add_action('wp_ajax_removeorderall', array($this, 'ajaxRemoveOrderAll'));
        add_action('wp_ajax_getViewForm', array($this, 'ajaxgetViewForm')); //Запрос формы
        add_action('wp_ajax_nopriv_getViewForm', array($this, 'ajaxgetViewForm')); //Запрос формы
        add_action('wp_ajax_getViewFormCustom', array($this, 'ajaxgetViewFormCustom')); //Запрос Кастомной формы
        add_action('wp_ajax_nopriv_getViewFormCustom', array($this, 'ajaxgetViewFormCustom')); //Запрос Кастомной формы
        add_action('wp_ajax_add_to_cart', array($this, 'add_to_cart')); //Добавление в корзину и отправка в оформление
        add_action('wp_ajax_nopriv_add_to_cart', array($this, 'add_to_cart')); //Добавление в корзину и отправка в оформление
        /**
         * Загрузка файла из формы
         */
        // add_action('wp_ajax_load_form_file', array($this, 'load_form_file'));
        // add_action('wp_ajax_nopriv_load_form_file', array($this, 'load_form_file'));
        
    }
    
    /**
     * Проверка обязательных полей
     */
    protected static function checkRequireField($form) {
        
        $options = Help::getInstance()->get_options();
        $params = $options['buyoptions'];
        if (empty($params)) {
            return true;
        }
        
        if (!empty($params['email_verifi']) && empty($form['user_email'])) {
            throw new Exception(__('Email field is required', 'coderun-oneclickwoo'), 200);
        }
        
        if (!empty($params['fio_verifi']) && empty($form['user_name'])) {
            throw new Exception(__('Name field is required', 'coderun-oneclickwoo'), 200);
        }
        
        if (!empty($params['fon_verifi']) && empty($form['user_phone'])) {
            throw new Exception(__('Telephone field is required', 'coderun-oneclickwoo'), 200);
        }
        
        if (!empty($params['dopik_verifi']) && empty($form['user_cooment'])) {
            throw new Exception(__('Message field is required', 'coderun-oneclickwoo'), 200);
        }
        
        if (!empty($params['conset_personal_data_enabled']) && empty(boolval($form['check_conset_personal_data']))) {
            throw new Exception(__('You need to give consent', 'coderun-oneclickwoo'), 200);
        }
        //Файлы
        if (isset($form['files']['name'])) {
            $files = array_filter($form['files']['name']);
        } else {
            $files = array();
        }
        
        if (!empty($params['upload_input_file_chek']) && !empty($params['upload_input_file_verifi']) && empty($files)) {
            throw new Exception(__('Attach file', 'coderun-oneclickwoo'), 200);
        }
    }
    
    /**
     * Выбросит исключение, если форма отправляетс чаще чем N секунд
     * @param int $product_id ид товара
     * @throws Exception
     */
    protected static function checkLimitSendForm($product_id) {
        
        $options = Help::getInstance()->get_options();
        
        $params = $options['buyoptions'];
        
        //Лимит отправки формы
        $limit_time = intval($params['time_limit_send_form']);
        
        $limit_message = __('You have already sent an order!', 'coderun-oneclickwoo');
        
        if (empty($limit_time)) {
            $limit_time = 10;
        }
        if (!empty($params['time_limit_message'])) {
            $limit_message = $params['time_limit_message'];
        }
        
        $key = 'ORDER_LAST_DATE_' . $product_id;
        
        if (empty($_SESSION['BUY_ONE_CLICK_WOOCOMMERCE'][$key])) {//Установка
            $_SESSION['BUY_ONE_CLICK_WOOCOMMERCE'][$key] = time();
        } else {
            if (($_SESSION['BUY_ONE_CLICK_WOOCOMMERCE'][$key] + $limit_time) > time()) {
                throw new \Exception($limit_message, 200);
            } else {
                $_SESSION['BUY_ONE_CLICK_WOOCOMMERCE'][$key] = time();
            }
        }
        //
    }
    
    /**
     * Функция выполняется после нажатия на кнопку в форме заказа
     */
    public function ajaxBuyButtonForm() {
        
        $arResult = array();
        
        $base_form_data = $_POST; //Весь запрос
        
        
        
        if(!wp_verify_nonce($base_form_data['_coderun_nonce'],'one_click_send')) {
            wp_send_json_error(array('message' => __('Something went wrong..', 'coderun-oneclickwoo')),200);
        }
        
        
        
        if (empty($base_form_data)) {
            wp_send_json_error(array('message' => __('request error', 'coderun-oneclickwoo')),200);
        }
        
        $help = Help::getInstance();
        
        $options = $help->get_options();
        
        if(!empty($options['buyoptions']['recaptcha_order_form'])) {
            $check_recaptcha = ReCaptcha::getInstance()->check($options['buyoptions']['recaptcha_order_form']);
            if($check_recaptcha['check']!==true) {
                wp_send_json_error(array('message' => $check_recaptcha['message']),200);
            }
        }
        
        $product_id = intval($help->get_value_field($base_form_data, 'idtovar'));
        
        $product_link = get_the_permalink($product_id);
        
        //
        
        /**
         * Поля формы и параметры метода
         */
        $field = array(
            'user_name' => $help->get_value_field($base_form_data, 'txtname'),
            'user_phone' => $help->get_value_field($base_form_data, 'txtphone'),
            'user_email' => sanitize_email($help->get_value_field($base_form_data, 'txtemail')),
            'user_cooment' => $help->get_value_field($base_form_data, 'message'),
            'order_comment' => $help->get_value_field($base_form_data, 'message'),
            'product_id' => $product_id,
            'product_name' => $help->get_value_field($base_form_data, 'nametovar'),
            'product_original_name' => $help->get_value_field($base_form_data, 'nametovar'),
            'product_price' => $help->get_value_field($base_form_data, 'pricetovar'),
            'product_link_admin' => '<a href="' . $product_link . '" target="_blank"><span class="glyphicon glyphicon-share"></span></a>',
            'product_link' => '<a href="' . $product_link . '" target="_blank">' . __('Look', 'coderun-oneclickwoo') . '</a>',
            'company_name' => $help->get_value_field($options['buynotification'], 'namemag'),
            'order_admin_comment' => $help->get_value_field($options['buynotification'], 'dopiczakaz'),
            'check_conset_personal_data' => $help->get_value_field($base_form_data, 'conset_personal_data'),
            'forms_field' => $help->get_form_data_legacy($base_form_data), //$help->get_value_field($base_form_data, 'form'),
            'order_time' => current_time('mysql'),
            'custom' => $help->get_value_field($base_form_data, 'custom'),
            'files' => !empty($_FILES['files']) ? $_FILES['files'] : null,
            'quantity_product' => $help->get_value_field($base_form_data, 'quantity_product'),
        );
        
        //@todo поле теперь отличается, нет name и value
        if ($help->module_variation) {
            if ($variation = \Coderun\BuyOneClick\VariationsAddition::getInstance()->getVariableProductInfo($field['forms_field'])) {
                
                $field['product_name'] .= '<br>' . $variation; // todo - убрать из этого поля
            }
            if (($variation_id = \Coderun\BuyOneClick\VariationsAddition::getInstance()->getVariationId($field['forms_field'])) > 0) {
                $product_id = $variation_id;
            }
        }
        
        try {
            if ($product_id < 1) {
                $this->logger->setInfo(__('An error has occurred! Order not formed', 'coderun-oneclickwoo'), ['productId' => $product_id]);
                throw new \Exception(__('An error has occurred! Order not formed', 'coderun-oneclickwoo'));
            }
            
            self::checkRequireField($field);
            self::checkLimitSendForm($product_id);
        } catch (Exception $ex) {
            $this->logger->setInfo($ex->getMessage());
            wp_send_json_error(array('message' => $ex->getMessage()));
        }
        
        $smslog = ''; //Лог смс
        
        // Копия для модификации в уведомлениях
        $copyField = $field;
        if (!empty($options['buynotification']['price_including_tax'])) {
            $wcOrder = Order::getInstance()->create_order(['product_id' => $product_id]);
            $copyField['product_price'] = Order::getInstance()->calculate_order_totals($wcOrder);
            $wcOrder->delete(true); // todo переделать
            unset($wcOrder);
        }
        
        //Отправка СМС клиенту
        if (Core::getInstance()->getOption('sms_enable_smsc', Core::OPTIONS_NOTIFICATIONS)) {
            $smsmessage = array(
                'fon' => $field['user_phone'],
                'fio' => $field['user_name'],
                'txtemail' => $field['user_email'],
                'dopinfo' => $field['order_admin_comment'],
                'price' => $field['product_price'],
                'nametov' => $field['product_original_name']
            );
            $sms = new BuySMSC();
            $smsTemplate = Core::getInstance()->getOption('sms_smshablon', Core::OPTIONS_NOTIFICATIONS);
            $smslog = $sms->send_sms(trim($smsmessage['fon']), BuyFunction::composeSms($smsTemplate, $smsmessage));
            ///Переписать функцию sms? помнить про static
        }
        //Отправка СМС продавцу
        if (Core::getInstance()->getOption('sms_enable_smsc_saller', Core::OPTIONS_NOTIFICATIONS)) {
            $smsmessage = array(
                'fon' => $field['user_phone'],
                'fio' => $field['user_name'],
                'txtemail' => $field['user_email'],
                'dopinfo' => $field['order_admin_comment'],
                'price' => $field['product_price'],
                'nametov' => $field['product_original_name']
            );
            $sms2 = new BuySMSC();
            $smsTemplate = Core::getInstance()->getOption('sms_smshablon_saller', Core::OPTIONS_NOTIFICATIONS);
            $smsPhoneSeller = Core::getInstance()->getOption('sms_phone_saller', Core::OPTIONS_NOTIFICATIONS);
            $smslog = $sms2->send_sms($smsPhoneSeller, BuyFunction::composeSms($smsTemplate, $smsmessage));
        }
        
        //Конец журналирования
        $arResult['message'] = __('The order has been sent', 'coderun-oneclickwoo');
        $arResult['result'] = $options['buyoptions']['success'];
        if (!empty($options['buyoptions']['upload_input_file_chek'])) {
            $arResult['files'] = LoadFile::getInstance()->load();
            $field['user_cooment'] .= '<br>' . $help->get_message_files_url($arResult['files']);
            $field['files_url'] = $help->get_message_files($arResult['files']);
            $copyField['files_url'] =  $field['files_url'];
            
            if (!empty(LoadFile::getInstance()->getErrors())) {
                $this->logger->setInfo(__('File upload error', 'coderun-oneclickwoo'), LoadFile::getInstance()->getErrors());
            }
        }
        
        if (empty($options['buyoptions']['add_tableorder_woo']) && !empty($field['user_email']) && !empty($options['buynotification']['infozakaz_chek'])) {
            BuyFunction::BuyEmailNotification($field['user_email'], $field['company_name'], $copyField);
        }
        if (!empty($options['buynotification']['emailbbc'])) {
            BuyFunction::BuyEmailNotification($options['buynotification']['emailbbc'], $field['company_name'], $copyField);
        }
        try {
            $woo_order_id = 0;
            //В таблицу Woo
            if (isset($options['buyoptions']['add_tableorder_woo']) and $field['custom'] == 0) {

                $woo_order_id = Order::getInstance()->set_order(
                    array(
                        'first_name' => $field['user_name'],
                        'last_name' => '',
                        'company' => '',
                        'email' => $field['user_email'],
                        'phone' => $field['user_phone'],
                        'address_1' => $field['order_comment'],
                        'address_2' => '',
                        'city' => '',
                        'state' => '',
                        'postcode' => '',
                        'country' => '',
                        'order_status' => 'processing', //Статус заказа который будет установлен
                        'message_notes_order' => __('Quick order form', 'coderun-oneclickwoo'), //Сообщение в заказе
                        'qty' => empty($field['quantity_product']) ? 1 : $field['quantity_product'],
                        'product_id' => $product_id, //ИД товара Woo
                    )
                );
            }
            
            $order_field = [
                'product_id' => $product_id,
                'product_name' => $field['product_original_name'],
                'product_meta' => null,
                'product_price' => $field['product_price'],
                'product_quantity'=> empty($field['quantity_product']) ? 1 : $field['quantity_product'],
                'form' => \wp_json_encode($field),
                'sms_log' => \wp_json_encode($smslog),
                'woo_order_id' => $woo_order_id,
                'user_id' => \get_current_user_id(),
            ];
            
            Order::getInstance()->save_order(
                $order_field
            );
            // Смена статуса заказа запускает Хуки отправки сообщений WooCommerce
            if ($woo_order_id) {
                $wcOrder = \wc_get_order($woo_order_id);
                if ($wcOrder instanceof \WC_Order) {
                     $wcOrder->update_status('processing', 'Quick order form');
                    if (isset($options['buyoptions']['success_action']) && intval($options['buyoptions']['success_action']) === 5) {
                        $arResult['redirectUrl'] = $wcOrder->get_checkout_order_received_url();
                    } else if (isset($options['buyoptions']['success_action']) && intval($options['buyoptions']['success_action']) === 6) {
                        $wcOrder->update_status('wc-pending');
                        $arResult['redirectUrl'] = $wcOrder->get_checkout_payment_url();
                    }
                } else {
                    throw new \Exception(__('Couldn\'t create WooCommerce order', 'coderun-oneclickwoo'). ' №'.$woo_order_id);
                }
            }
            
        } catch (Exception $ex) {
            $this->logger->setInfo($ex->getMessage());
        }
        
        BuyHookPlugin::buyClickNewrder($arResult, $order_field);
        
        wp_send_json_success($arResult);
    }
    
    /**
     * Функция удаляет элемент заказа из таблицы
     * Данные отправляются из файла admin_order.js
     */
    public function ajaxRemoveOrderId() {
        
        // Удаление записи журнала плагина
        if(!empty($_POST['text'])) {
            $order_id = intval($_POST['text']);
            Order::getInstance()->deactive_order($order_id);
            wp_send_json_success();
        }elseif(!empty($_POST['orderId']) && !empty($_POST['pluginId'])){ //Удаление заказа
            $order_id = $_POST['orderId'];
            $plugin_id = $_POST['pluginId'];
            $order = Order::getInstance()->get_wc_order($order_id);
            if(!empty($order['woo_order_id'])) {
                if(!Help::getInstance()->isset_woo_order($order['woo_order_id'])) {
                    wp_send_json_error();
                }
                $order = new \WC_Order($order['woo_order_id']);
                if($order instanceof \WC_Order && $order->delete()){
                    wp_send_json_success();
                }
            }
        }
        
        wp_send_json_error();
        
    }
    
    /**
     * Функция удаляет всю таблицу заказов
     * Данные отправляются из файла admin_order.js
     */
    public function ajaxRemoveOrderAll() {
        $nonce = $_POST['nonce']; // Массив URL и NONCE
        ob_end_clean();
        if (wp_verify_nonce($nonce['nonce'], 'superKey')) {
            Order::getInstance()->remove_order_all();
            wp_send_json_success('ok');
        } else {
            wp_send_json_error('error');
        }
    }
    
    /**
     * Функция Изменения статуса заказа
     * Данные отправляются из файла admin_order.js
     */
    public function ajaxStatusOrderId() {
        $text = $_POST['text'];
        $id = $text['id'];
        Order::getInstance()->update_status($id,intval($text['status']));
        wp_send_json_success();
    }
    
    public static function add_to_cart() {
        $productid = intval($_POST['productid']);
        $variation_id = intval($_POST['variation_selected']);
        $variation_attr = '';
        $variations = array();
        $quantity = 1;
        if (isset($_POST['variation_attr'])) {
            $variation_attr = $_POST['variation_attr'];
            $arSelectVariation = explode('&', $variation_attr);
            
            foreach ($arSelectVariation as $values) {
                $params = explode('=', $values);
                if (stripos($params[0], 'attribute_pa') !== false) {
                    $variation_slug = str_replace('attribute_pa_', '', $params[0]);
                    $variation_value = $params[1];
                    $variations[$variation_slug] = $variation_value;
                }
                if (stripos($params[0], 'quantity') !== false) {
                    $quantity = $params[1];
                }
            }
        }
        
        if (!function_exists('WC')) {
            echo get_home_url();
            die();
        }
        
        
        WC()->cart->add_to_cart($productid, $quantity, $variation_id, $variations);
        
        $url = get_permalink(get_option('woocommerce_checkout_page_id'));
        
        echo $url;
        
        die();
    }
    
    /**
     * Возвращает форму для быстрого заказа
     * Используется для фронта
     */
    public static function ajaxgetViewForm() {
        
        $product_id = intval($_POST['productid']);
        
        $variation_id = intval($_POST['variation_selected']);
        
        if ($variation_id > 0) {
            $product_id = $variation_id;
        }
        
        $cartinfo = BuyFunction::get_product_param($product_id);
        
        $cartinfo['custom'] = 0;
        
        echo BuyFunction::viewBuyForm($cartinfo);
        
        die();
    }
    
    public function load_form_file() {
        
        wp_send_json_success(LoadFile::getInstance()->load());
    }
    
    public static function ajaxgetViewFormCustom() {
        $url = $_POST['urlpost'];
        $productid = $_POST['productid'];
        $name = $_POST['name'];
        $count = $_POST['count'];
        $price = $_POST['price'];
        $arProduct = array(
            'article' => $productid,
            'name' => $name,
            'imageurl' => '',
            'amount' => $price,
            'quantity' => $count,
            'custom' => 1,
        );
        
        echo BuyFunction::viewBuyForm($arProduct);
        die();
    }
    
}
