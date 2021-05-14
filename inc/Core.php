<?php

namespace Coderun\BuyOneClick;

use Coderun\BuyOneClick\Help;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Базовый класс плагина
 * Создаёт настройки, отображает опции в админки
 * Вызывает хуки ВордПресс
 */
class Core {
    
    /**
     * Полное название плагина
     */
    const NAME_PLUGIN = 'Buy one click WooCommerce';
    
    /**
     * Имя папки с плагином без слэшей
     */
    const PATCH_PLUGIN = 'buy-one-click-woocommerce';
    
    /**
     * Название пункта подменю
     */
    const NAME_SUB_MENU = 'BuyOneClick';
    
    /**
     * URL страницы подменю
     */
    const URL_SUB_MENU = 'buyone';
    
    /**
     * Путь до страницы опций плагина HTML
     */
    const OPTIONS_NAME_PAGE = 'page/option1.php';
    
    /**
     * Имя индексного файла
     */
    const INDEX_NAME_FILE = 'buycli-index.php';
    
    /**
     * Версия ядра
     */
    const VERSION = '1.9.7';
    
    protected static $_instance = null;
    
    /**
     * Настройки плагина
     * @uses [enable_button] - Включатель кнопки
     * @uses [enable_button_shortcod] Показ кнопки шорткода
     * @uses [namebutton] - Название кнопки "купить"
     * @uses [positionbutton]- Расположение кнопк "купить"
     * @uses [infotovar_chek] - Показывать или нет информацию о товаре в окне
     * @uses [fio_chek] - Запрос на ФИО
     * @uses [fon_chek] - Запрос телефона
     * @uses [email_chek] - Запрос email
     * @uses [dopik_chek] - Показывать поле дополнительной информации
     * @uses [fio_descript] - Описание поля ФИО
     * @uses [fon_descript] - Описание поля телефон
     * @uses [email_descript] - Описание поля email
     * @uses [dopik_descript] - Описание поля email
     * @uses [butform_descript] - Название кнопки в форме отправки данных о покупателе
     * @uses [infotovar_chek] - Показывать или нет информацию о товаре в окне
     * @uses [success] - Сообщение об успешном совершение заказа в форме
     * @uses [fio_verifi] - Обязательно поле ФИО
     * @uses [fon_verifi] - Обязательно поле ФИО
     * @uses [fon_format] - Формат телефона
     * @uses [email_verifi] - Обязательно поле ФИО
     * @uses [dopik_verifi] - Обязательно поле ФИО
     * @uses [success_action] - radio действия после закрытия
     * @uses [success_action_close] - Время в мс до закрытия формы заказа
     * @uses [success_action_message] - Сообщение после заказа
     * @uses [success_action_redirect] - URL редиректа после заказа
     * @uses [regex_fon] Регулярное выражение проверки телефона
     * @uses [add_tableorder_woo] Включает добавление заказов в таблицу Woo
     *
     */
    static $buyoptions;
    
    /**
     * Настройки уведомлений плагина
     * @uses [namemag] - Название магазина
     * @uses [emailfrom] - Email для ответов
     * @uses [emailbbc] - Email для копий
     * @uses [infozakaz_chek] - Отправка клиенту сообщения о заказе
     * @uses [dopiczakaz_chek] - Отправка клиенту доп сообщения
     * @uses [dopiczakaz] - Дополнительная информация
     */
    static $buynotification;
    
    /**
     * @deprecated Заказы теперь сохраняются в свою таблицу смотри Order.php
     * Журнал заказов. Вложенные массивы имеют следующие данные
     * @uses [time] - Время создания заказа
     * @uses [idtovar] - ID товара или записи Wordpress
     * @uses [txtname] - ФИО клиента
     * @uses [txtphone] - Номер телефона клиента
     * @uses [txtemail] - Email клиента
     * @uses [nametovar] - Название товара
     * @uses [pricetovar] - Цена товара
     * @uses [message] - Сообщение от клиента
     * @uses [smslog] - Лог СМС
     */
    static $buyzakaz;
    
    /**
     * Опции СМСЦЕНТРА
     * @uses [login] - логин пользователя
     * @uses [password] - Пароль или MD5-хеш пароля в нижнем регистре
     * @uses [methodpost] - Использовать метод POST
     * @uses [https] - использовать HTTPS протокол
     * @uses [charset] -кодировка сообщения: utf-8, koi8-r или windows-1251 (по умолчанию)
     * @uses [debug] - флаг отладки
     * @uses [smtpfrom] - e-mail адрес отправителя
     * @uses [smshablon] - SMS шаблон
     * @uses [enable_smsc] - Вкючение СМС отправки на сайте
     */
    static $buysmscoptions;
    
    /**
     * Работа с вариативными товарами
     * @var type
     */
    public static $variation = FALSE;
    
    /**
     * @deprecated
     * @var array|type
     */
    protected $options = array();
    
    /**
     * Все настройки плагина
     *
     */
    protected $optionsPull = [
        'buyoptions' => [],
        'buynotification'=> [],
        'buysmscoptions'=> [],
    ];
    
    /**
     * Singletone
     * @return Core
     */
    public static function getInstance() {
        
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function __clone() {
        throw new \Exception('Forbiden instance __clone');
    }
    
    public function __wakeup() {
        throw new \Exception('Forbiden instance __wakeup');
    }
    
    /**
     * Конструктор класса
     */
    protected function __construct() {
        
        $help = Help::getInstance();
        
        $this->options = $options = $help->get_options();
        
        if (class_exists('BuyVariationClass')) {
            
            $help->module_variation = true;
            
            self::$variation = $help->module_variation;
        }
        
        self::$buyoptions = $options['buyoptions']; //Загрука опций из базы
        //self::$buyzakaz = $options['buyzakaz']; //Загрука опций из базы
        self::$buynotification = $options['buynotification']; //Загрука опций из базы
        self::$buysmscoptions = $options['buysmscoptions']; //Получаем настройки смсцентра из опций
        // $this->addAction();
        // $this->addOptions();
    }
    
    /**
     * Подключение функций через add_action Wordpress
     */
    public function addAction() {
    
        require_once (CODERUN_ONECLICKWOO_PLUGIN_DIR . '/inc/Logger.php');
        
        $buyoptions = $this->options['buyoptions'];
        if (!empty($buyoptions['enable_button']) and $buyoptions['enable_button'] == 'on') {
            $position = $buyoptions['positionbutton']; //Позиция кнопки
            if (self::$variation) {
                $strPosition = \BuyVariationClass::getPositionButton();
                if ($strPosition !== FALSE) {
                    $position = $strPosition;
                }
            }
            add_action($position, array($this, 'styleAddFrontPage')); //Стили фронта
            add_action($position, array($this, 'scriptAddFrontPage')); //Скрипты фронта
            add_action($position, array('BuyFunction', 'viewBuyButton')); //Кнопка заказать
            //Положение в категории товаров
            if (!empty($buyoptions['enable_button_category']) and $buyoptions['enable_button_category'] == 'on') {
                $position_category = $buyoptions['positionbutton_category']; //Позиция кнопки
                add_action($position_category, array('BuyFunction', 'viewBuyButton')); //Кнопка заказать
                add_action($position_category, array($this, 'styleAddFrontPage')); //Стили фронта
                add_action($position_category, array($this, 'scriptAddFrontPage')); //Скрипты фронта
            }
            // Положение для кнопки "Товара нет в наличие"
            
            $position = $buyoptions['positionbutton_out_stock'];
            
            if(strlen($position)>5) {
                add_filter('woocommerce_get_stock_html', function ($html) {
                    global $product;
                    if(is_object($product) && $product instanceof \WC_Product && method_exists('WC_Product','get_availability')) {
                        $this->styleAddFrontPage();
                        $this->scriptAddFrontPage();
                        $availability = $product->get_availability();
                        // Товар имеет статус не в наличие
                        if(strlen($html)>1 && isset($availability['class']) && $availability['class']==='out-of-stock') {
                            if(!$product->is_type('variable')) { // Не показывать в вариативных, Woo по умолчанию оставляет обычную кнопку
                                $html .= \BuyFunction::viewBuyButton(true);
                            }
                        }
                    }
                    
                    return $html;
                }
                );
            }
            
            
            
        }
        
        $service = Service::getInstance();
        add_action('woocommerce_email_before_order_table', [$service, 'modificationOrderTemplateWooCommerce'], 10, 3);
        add_action('wp_head', array($this, 'jsVariableHead'));
        
    }
    
    public function action_admin_page() {
        add_action('admin_menu', array($this, 'adminOptions'));
        
        add_filter('plugin_action_links', array($this, 'pluginLinkSetting'), 10, 2); //Настройка на странице плагинов
    }
    
    /**
     * Создаёт переменные в шапке, одна из них это обработчик ajax
     */
    public function jsVariableHead() {
        
        $buyoptions = $this->options['buyoptions'];
        
        $variables = array('ajaxurl' => admin_url('admin-ajax.php'));
        if (self::$variation) {
            $variables['variation'] = 1;
        } else {
            $variables['variation'] = 0;
        }
        
        //Формат телефона 
        
        if (isset($buyoptions['fon_format_input']) && strlen($buyoptions['fon_format_input']) > 3) {
            $buyoptions['fon_format_input'] = str_replace(['\'', '"'], [], $buyoptions['fon_format_input']);
            $variables['tel_mask'] = $buyoptions['fon_format_input'];
        }
        
        //Режим работы плагина
        
        if (isset($buyoptions['plugin_work_mode'])) {
            $variables['work_mode'] = intval($buyoptions['plugin_work_mode']);
        } else {
            $variables['work_mode'] = 0;
        }
        
        if (isset($buyoptions['success_action'])) {
            $variables['success_action'] = intval($buyoptions['success_action']);
            if (!empty($buyoptions['success_action_close'])) {
                $variables['after_submit_form'] = intval($buyoptions['success_action_close']); // 2 Закрытие формы через мсек
            }
            if (!empty($buyoptions['success_action_message'])) {
                $variables['after_submit_form'] = $buyoptions['success_action_message']; // 3 Сообщение после нажатия кнопки в форме
            }
            if (!empty($buyoptions['success_action_redirect'])) {
                $variables['after_submit_form'] = $buyoptions['success_action_redirect']; // 4  Редирект на страницу после нажатия на кнопку в форме
            }
            
            if (!empty($buyoptions['success'])) {
                $variables['after_message_form'] = $buyoptions['success'];
            }
        }
        
        
        $str = '';
        $str .= "<script type=\"text/javascript\">\n";
        $str .= " /* <![CDATA[ */\n";
        $str .= "var buyone_ajax = " . json_encode($variables) . "; \n";
        $str .= " /* ]]> */\n";
        // $str .=
        $str .= "</script>\n";
        echo $str;
    }
    
    /**
     * Операции выполняемые при деактивации плагина
     */
    public function deactivationPlugin() {
        // delete_option('buyoptions');
        // delete_option('buyzakaz');
        //delete_option('buynotification');
        //delete_option('buysmscoptions');
        remove_shortcode('viewBuyButton');
    }
    
    /**
     * Добавление опций в базу Wordpress при активации
     */
    public function addOptions() {
        add_option('buyoptions', array()); //массив настроек плагина
        // add_option('buyzakaz', array()); //массив Заказов через форму
        add_option('buynotification', array()); //Массив настроек уведомлений
        add_option('buysmscoptions', array()); //Настройки smsc
        PluginUpdate::createOrderTable();
    }
    
    /**
     * Меню или суб меню плагина
     */
    public function adminOptions() {
        //Подключается если есть менюя от Woocommerce
        $page_option = add_submenu_page('woocommerce', self::NAME_SUB_MENU, self::NAME_SUB_MENU, 'manage_woocommerce', self::URL_SUB_MENU, array($this, 'showSettingPage'));
        add_action('admin_print_styles-' . $page_option, array($this, 'styleAddpage')); //загружаем стили только для страницы плагина
        add_action('admin_print_scripts-' . $page_option, array($this, 'scriptAddpage')); //Скрипты
    }
    
    /**
     * Стили для страницы плагина
     */
    public function styleAddpage() {
        wp_register_style('buybootstrapcss1', plugins_url() . '/' . self::PATCH_PLUGIN . '/' . 'bootstrap/css/bootstrap.css');
        wp_enqueue_style('buybootstrapcss1');
        wp_register_style('buyadmincss2', plugins_url() . '/' . self::PATCH_PLUGIN . '/' . 'css/admin.css');
        wp_enqueue_style('buyadmincss2');
    }
    
    /**
     * Скрипты для страницы плагина
     */
    public function scriptAddpage() {
        wp_enqueue_script('buybootstrapjs1', plugins_url() . '/' . self::PATCH_PLUGIN . '/' . 'bootstrap/js/bootstrap.js', ['jquery'], self::VERSION);
        wp_enqueue_script('buyorder', plugins_url() . '/' . self::PATCH_PLUGIN . '/' . 'js/admin_order.js', ['jquery'], self::VERSION);
        
        
        wp_localize_script('buyorder', 'buyadminnonce', array(//Установка проверочного кода
                                                              'url' => admin_url(plugins_url() . '/' . self::PATCH_PLUGIN . '/' . 'js/admin_order.js'),
                                                              'nonce' => wp_create_nonce('superKey')
        ));
    }
    
    /**
     * Стили для фронтэнда
     */
    public function styleAddFrontPage() {
        
        foreach ($this->getStylesFront() as $styleName => $styleParams) {
            wp_register_style($styleName, $styleParams['url'], $styleParams['deps']);
            wp_enqueue_style($styleName);
        }
    }
    
    /**
     * Стили для фронта
     * @return array [][url,path,deps]
     */
    public function getStylesFront()
    {
        $numForm = 1;
        $buyoptions = Help::getInstance()->get_options('buyoptions');
        if (isset($buyoptions['form_style_color'])) {
            $numForm = intval($buyoptions['form_style_color']);
        }
        $wp_uploads_dir = wp_get_upload_dir();
        $styles = [];
        if (file_exists($wp_uploads_dir['basedir'] . '/' . self::PATCH_PLUGIN . '/css/general.css')) {
            $styles['buyonclickfront-general'] = [
                'url' => $wp_uploads_dir['baseurl'] . '/' . self::PATCH_PLUGIN . '/css/general.css',
                'path' => $wp_uploads_dir['basedir'] . '/' . self::PATCH_PLUGIN . '/css/general.css',
                'deps' => [],
            ];
        } elseif (file_exists(get_stylesheet_directory() . '/' . self::PATCH_PLUGIN . '/css/general.css')) {
            $styles['buyonclickfront-general'] = [
                'url' => get_stylesheet_directory_uri() . '/' . self::PATCH_PLUGIN . '/css/general.css',
                'path' => get_stylesheet_directory() . '/' . self::PATCH_PLUGIN . '/css/general.css',
                'deps' => [],
            ];
        } else {
            $styles['buyonclickfront-general'] = [
                'url' =>  plugins_url() . '/' . self::PATCH_PLUGIN . '/templates/css/general.css',
                'path' => CODERUN_ONECLICKWOO_PLUGIN_DIR . '/templates/css/general.css',
                'deps' => [],
            ];
        }
        
        
        if (file_exists($wp_uploads_dir['basedir'] . '/' . self::PATCH_PLUGIN . '/css/form_' . $numForm . '.css')) {
            $styles['buyonclickcss2'] = [
                'url' =>   $wp_uploads_dir['baseurl'] . '/' . self::PATCH_PLUGIN . '/css/form_' . $numForm . '.css',
                'path' => $wp_uploads_dir['basedir'] . '/' . self::PATCH_PLUGIN . '/css/form_' . $numForm . '.css',
                'deps' => ['buyonclickfront-general'],
            ];
        } elseif (file_exists(get_stylesheet_directory() . '/' . self::PATCH_PLUGIN . '/css/form_' . $numForm . '.css')) {
            $styles['buyonclickcss2'] = [
                'url' =>   get_stylesheet_directory_uri() . '/' . self::PATCH_PLUGIN . '/css/form_' . $numForm . '.css',
                'path' => get_stylesheet_directory() . '/' . self::PATCH_PLUGIN . '/css/form_' . $numForm . '.css',
                'deps' => ['buyonclickfront-general'],
            ];
        } else {
            $styles['buyonclickcss2'] = [
                'url' =>   plugins_url() . '/' . self::PATCH_PLUGIN . '/templates/css/form_' . $numForm . '.css',
                'path' => CODERUN_ONECLICKWOO_PLUGIN_DIR . '/templates/css/form_' . $numForm . '.css',
                'deps' => ['buyonclickfront-general'],
            ];
        }
        
        if (file_exists($wp_uploads_dir['basedir'] . '/' . self::PATCH_PLUGIN . '/css/formmessage.css')) {
            $styles['buyonclickfrontcss3'] = [
                'url' =>   $wp_uploads_dir['baseurl'] . '/' . self::PATCH_PLUGIN . '/css/formmessage.css',
                'path' => $wp_uploads_dir['basedir'] . '/' . self::PATCH_PLUGIN . '/css/formmessage.css',
                'deps' => ['buyonclickfront-general'],
            ];
        } elseif (file_exists(get_stylesheet_directory() . '/' . self::PATCH_PLUGIN . '/css/formmessage.css')) {
            $styles['buyonclickfrontcss3'] = [
                'url' =>   get_stylesheet_directory_uri() . '/' . self::PATCH_PLUGIN . '/css/formmessage.css',
                'path' => get_stylesheet_directory() . '/' . self::PATCH_PLUGIN . '/css/formmessage.css',
                'deps' => ['buyonclickfront-general'],
            ];
        } else {
            $styles['buyonclickfrontcss3'] = [
                'url' =>   plugins_url() . '/' . self::PATCH_PLUGIN . '/templates/css/formmessage.css',
                'path' => CODERUN_ONECLICKWOO_PLUGIN_DIR . '/templates/css/formmessage.css',
                'deps' => ['buyonclickfront-general'],
            ];
        }
        
        $styles['loading'] = [
            'url' =>   plugins_url() . '/' . self::PATCH_PLUGIN . '/css/loading-btn/loading.css',
            'path' => CODERUN_ONECLICKWOO_PLUGIN_DIR . '/css/loading-btn/loading.css',
            'deps' => [],
        ];
        $styles['loading-btn'] = [
            'url' =>   plugins_url() . '/' . self::PATCH_PLUGIN . '/css/loading-btn/loading-btn.css',
            'path' => CODERUN_ONECLICKWOO_PLUGIN_DIR . '/css/loading-btn/loading-btn.css',
            'deps' => [],
        ];
        
        return $styles;
        
    }
    
    /**
     * Скрипты для фронтэнда
     */
    public function scriptAddFrontPage() {
        wp_enqueue_script('buyonclickfrontjs', plugins_url() . '/' . self::PATCH_PLUGIN . '/' . 'js/form.js', ['jquery', 'buymaskedinput'], self::VERSION);
        wp_enqueue_script('buymaskedinput', plugins_url() . '/' . self::PATCH_PLUGIN . '/' . 'js/jquery.maskedinput.min.js', ['jquery'], self::VERSION);
    }
    
    /**
     * Страница плагина
     */
    public function showSettingPage() {
        include_once WP_PLUGIN_DIR . '/' . self::PATCH_PLUGIN . '/' . self::OPTIONS_NAME_PAGE;
    }
    
    /**
     * Активная вкладка в админпанели плагина
     * @return string css Класс для активной вкладки
     */
    public function adminActiveTab($tab_name = null, $tab = null) {
        
        if (isset($_GET['tab']) && !$tab)
            $tab = $_GET['tab'];
        else
            $tab = 'general';
        
        $output = '';
        if (isset($tab_name) && $tab_name) {
            if ($tab_name == $tab)
                $output = ' nav-tab-active';
        }
        echo $output;
    }
    
    /**
     * Подключает нужную страницу исходя из вкладки на страницы настроек плагина
     * @result include_once tab{номер вкладки}-option1.php
     */
    public function tabViwer() {
        
        if (isset($_GET['tab'])) {
            $tab = $_GET['tab'];
            switch ($tab) {
                case 'notification':
                    include_once WP_PLUGIN_DIR . '/' . self::PATCH_PLUGIN . '/page/tab2-option1.php';
                    break;
                case 'orders':
                    include_once WP_PLUGIN_DIR . '/' . self::PATCH_PLUGIN . '/page/tab3-option1.php';
                    break;
                case 'help':
                    include_once WP_PLUGIN_DIR . '/' . self::PATCH_PLUGIN . '/page/tab4-option1.php';
                    break;
                default :
                    include_once WP_PLUGIN_DIR . '/' . self::PATCH_PLUGIN . '/page/tab1-option1.php';
            }
        } else {
            include_once WP_PLUGIN_DIR . '/' . self::PATCH_PLUGIN . '/page/tab1-option1.php';
        }
    }
    
    /**
     * Добавляет пункт настроек на странице активированных плагинов
     */
    public function pluginLinkSetting($links, $file) {
        $this_plugin = self::PATCH_PLUGIN . '/' . self::INDEX_NAME_FILE;
        if ($file == $this_plugin) {
            $settings_link1 = '<a href="admin.php?page=' . self::URL_SUB_MENU . '">' . __("Settings", "default") . '</a>';
            array_unshift($links, $settings_link1);
        }
        return $links;
    }
    
    public static function get_template_path() {
        return self::PATCH_PLUGIN;
    }
    
    /**
     * Вернёт нужную настройку
     * @param        $key Ключ опции относящийся к $optionsBush
     * @param string $optionsBush раздел настроек
     * @param string $defaultValue значение по умолчанию, если нет опции
     *
     * @return mixed|string
     * @throws \Exception
     */
    public function getOption($key, $optionsBush = 'buyoptions', $defaultValue = '')
    {
        
        if (!\array_key_exists($optionsBush, $this->optionsPull) || empty($optionsBush)) {
            throw new \Exception(sprintf('Invalid settings key: %s', $optionsBush));
        }
        
        
        if (empty($this->optionsPull[$optionsBush])) {
            $this->optionsPull[$optionsBush] = \get_option($optionsBush, []);
        }
        
        if (isset($this->optionsPull[$optionsBush][$key])) {
            return $this->optionsPull[$optionsBush][$key];
        }
        return $defaultValue;
    }
    
}
