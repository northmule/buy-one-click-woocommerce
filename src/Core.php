<?php

namespace Coderun\BuyOneClick;

use Coderun\BuyOneClick\Controller\AdminController;
use Coderun\BuyOneClick\Controller\CartController;
use Coderun\BuyOneClick\Controller\FormController;
use Coderun\BuyOneClick\Controller\OrderController;
use Coderun\BuyOneClick\Options\General as GeneralOptions;
use Coderun\BuyOneClick\Options\Notification as NotificationOptions;
use Coderun\BuyOneClick\Options\Marketing as MarketingOptions;
use Exception;
use WC_Product;
use Coderun\BuyOneClick\Constant\Options\Type as OptionsType;

use function array_key_exists;
use function class_exists;
use function file_exists;
use function method_exists;
use function get_option;

/**
 * Базовый класс плагина
 * Создаёт настройки, отображает опции в админки
 * Вызывает хуки ВордПресс
 */
class Core
{
    /**
     * Полное название плагина
     */
    public const NAME_PLUGIN = 'Buy one click WooCommerce';

    /**
     * Имя папки с плагином без слэшей
     */
    public const PATCH_PLUGIN = 'buy-one-click-woocommerce';

    /**
     * Название пункта подменю
     */
    public const NAME_SUB_MENU = 'BuyOneClick';

    /**
     * URL страницы подменю
     */
    public const URL_SUB_MENU = 'buyone';

    /**
     * Путь до страницы опций плагина HTML
     */
    public const OPTIONS_NAME_PAGE = 'page/option1.php';

    /**
     * Имя индексного файла
     */
    public const INDEX_NAME_FILE = 'buycli-index.php';

    public const OPTIONS_MARKETING = OptionsType::MARKETING;

    public const OPTIONS_GENERAL = OptionsType::GENERAL;

    public const OPTIONS_DESIGN_FORM = OptionsType::DESIGN_FORM;
    /**
     * Вкладка Уведомлений
     */
    public const OPTIONS_NOTIFICATIONS = OptionsType::NOTIFICATIONS;

    public const OPTIONS_SMS = OptionsType::SMS;

    /**
     * Версия ядра
     */
    public const VERSION = '2.0.0';
    
    /**
     * @var Core|null
     */
    protected static ?Core $_instance = null;

    /**
     * Работа с вариативными товарами
     * @var type
     */
    public static $variation = false;

    /**
     * Настройки плагина
     *
     * @var GeneralOptions
     */
    protected GeneralOptions $commonOptions;
    /**
     * Настройки плагина
     *
     * @var NotificationOptions
     */
    protected NotificationOptions $notificationOptions;

    /**
     * Настройки плагина
     *
     * @var MarketingOptions
     */
    protected MarketingOptions $marketingOptions;

    /**
     * Все настройки плагина
     * с значением по умолчанию
     *
     * @var array<string, array>
     */
    protected array $optionsPull = [
        self::OPTIONS_GENERAL => [],
        self::OPTIONS_NOTIFICATIONS => [],
        self::OPTIONS_MARKETING => [],
        self::OPTIONS_DESIGN_FORM => [],
    ];

    /**
     * Singletone
     * @return Core
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Конструктор класса
     */
    protected function __construct()
    {
        add_action('init', [$this, 'initOptions']);
        add_action('init', [$this, 'initializeAdditions']);
        add_action('init', [$this, 'initAction']);
        add_action('admin_init', [$this, 'registeringSettings']); // Инициализация допустимых настроек
        add_action('init', [\Coderun\BuyOneClick\BuyHookPlugin::class, 'load']);
        add_action('init', [\Coderun\BuyOneClick\ShortCodes::class, 'getInstance']);
        // todo сделать настройку
        add_action('woocommerce_email_before_order_table', [Service::getInstance(), 'modificationOrderTemplateWooCommerce'], 10, 3);
        add_action('wp_head', [$this, 'frontVariables']);
        // Обработчики запросов
        $this->initController();

        $this->initAdminPages();
    }
    
    /**
     * Контроллеры
     *
     * @return void
     */
    protected function initController(): void
    {
        add_action('init', static function () {
            (new OrderController())->init();
        });
        add_action('init', static function () {
            (new FormController())->init();
        });
        add_action('init', static function () {
            (new CartController())->init();
        });
        add_action('init', static function () {
            (new AdminController())->init();
        });
    }
    
    /**
     * Инициализация основного функционала
     * Зацеп для отрисовки кнопок
     *
     * @return void
     */
    public function initAction(): void
    {
        if ($this->commonOptions->isEnableButton()) {
            $locationInProductCard = $this->commonOptions->getPositionButton(); //Позиция кнопки
            if (self::$variation) {
                $positionInVariations = VariationsAddition::getInstance()->getPositionButton();
                if ($positionInVariations !== false) {
                    $locationInProductCard = $positionInVariations;
                }
            }
            add_action($locationInProductCard, [$this, 'styleAddFrontPage']); //Стили фронта
            add_action($locationInProductCard, [$this, 'scriptAddFrontPage']); //Скрипты фронта
            add_action($locationInProductCard, [BuyFunction::class, 'viewBuyButton']); //Кнопка заказать
            //Положение в категории товаров
            if ($this->commonOptions->isEnableButtonCategory()) {
                $locationInCategory = $this->commonOptions->getButtonPositionInCategory(); //Позиция кнопки
                add_action($locationInCategory, [BuyFunction::class, 'viewBuyButton']); //Кнопка заказать
                add_action($locationInCategory, [$this, 'styleAddFrontPage']); //Стили фронта
                add_action($locationInCategory, [$this, 'scriptAddFrontPage']); //Скрипты фронта
            }
            if (strlen($this->commonOptions->getPositionButtonOutStock()) > 5) {
                add_filter('woocommerce_get_stock_html',
                    function ($html) {
                        global $product;
                        if ($product instanceof WC_Product && method_exists('WC_Product', 'get_availability')) {
                            $this->styleAddFrontPage();
                            $this->scriptAddFrontPage();
                            $availability = $product->get_availability();
                            // Товар имеет статус не в наличие
                            if (strlen($html) > 1 && isset($availability['class']) && $availability['class'] === 'out-of-stock') {
                                if (!$product->is_type('variable')) { // Не показывать в вариативных, Woo по умолчанию оставляет обычную кнопку
                                    $html .= BuyFunction::viewBuyButton(true);
                                }
                            }
                        }
                        return $html;
                    });
            }
        }
    }

    /**
     * Инициализация настроек, настройка объектов
     * @return void
     */
    public function initOptions()
    {
        $help = Help::getInstance();
        $this->commonOptions = new GeneralOptions(get_option(OptionsType::GENERAL, []));
        $this->notificationOptions = new NotificationOptions(get_option(OptionsType::NOTIFICATIONS, []));
        $this->marketingOptions = new MarketingOptions(get_option(OptionsType::MARKETING, []));
    }


    /**
     * Поздняя инициализация дополнений
     *
     * @return void
     */
    public function initializeAdditions(): void
    {
        $help = Help::getInstance();
        do_action('buy_one_click_woocommerce_start_load_core');
        if (class_exists('\Coderun\BuyOneClick\VariationsAddition')) {
            $help->module_variation = true;
            self::$variation = $help->module_variation;
        }
    }
    
    /**
     * @return void
     */
    protected function initAdminPages(): void
    {
        add_action('admin_menu', [$this, 'adminOptions']);
        add_filter('plugin_action_links', [$this, 'pluginLinkSetting'], 10, 2); //Настройка на странице плагинов
    }
    
    /**
     * Переменны для фронта
     * Выводятся как JS переменные
     *
     * @return void
     * @throws Exception
     */
    public function frontVariables(): void
    {
        $variables = ['ajaxurl' => admin_url('admin-ajax.php')];
        $variables['variation'] = self::$variation ? 1 : 0;
        $variables['tel_mask'] = str_replace(['\'', '"'], [], $this->commonOptions->getPhoneNumberInputMask());
        $variables['work_mode'] = $this->commonOptions->getPluginWorkMode();
        $variables['success_action'] = $this->commonOptions->getActionAfterSubmittingForm();
        if ($this->commonOptions->getActionAfterSubmittingForm() !== 0) {
            if ($this->commonOptions->getSecondsBeforeClosingForm()) {
                $variables['after_submit_form'] = $this->commonOptions->getSecondsBeforeClosingForm(); // 2 Закрытие формы через мсек
            }
            if ($this->commonOptions->getMessageAfterSubmittingForm()) {
                $variables['after_submit_form'] = $this->commonOptions->getMessageAfterSubmittingForm(); // 3 Сообщение после нажатия кнопки в форме
            }
            if ($this->commonOptions->getUrlRedirectAddress()) {
                $variables['after_submit_form'] = $this->commonOptions->getUrlRedirectAddress(); // 4  Редирект на страницу после нажатия на кнопку в форме
            }
            $variables['after_message_form'] = $this->commonOptions->getSubmittingFormMessageSuccess();
        }
        if ($this->marketingOptions->getAfterClickingOnButton()) {
            $variables['callback_after_clicking_on_button'] = $this->marketingOptions->getAfterClickingOnButton();
        }
        if ($this->marketingOptions->getSuccessfulFormSubmission()) {
            $variables['callback_successful_form_submission'] = $this->marketingOptions->getSuccessfulFormSubmission();
        }
        $variables['yandex_metrica'] = [
            'transfer_data_to_yandex_commerce' => $this->marketingOptions->isTransferDataToYandexCommerce(),
            'data_layer' => $this->marketingOptions->getNameOfYandexMetricaDataContainer(),
            'goal_id' => $this->marketingOptions->getGoalIdInYandexECommerce(),
        ];
        $variables['add_an_order_to_woo_commerce'] = $this->commonOptions->isAddAnOrderToWooCommerce();
   
        $outputList = [
            sprintf('<script type="text/javascript">%s', "\n"),
            sprintf('let buyone_ajax = %s;%s', json_encode($variables), "\n"),
            sprintf(
                'window.%s = window.%s || [];%s',
                $this->marketingOptions->getNameOfYandexMetricaDataContainer(),
                $this->marketingOptions->getNameOfYandexMetricaDataContainer(),
                "\n"
            ),
            sprintf('</script>%s', "\n"),
        ];
        foreach ($outputList as $value) {
            echo $value;
        }
    }

    /**
     * Операции выполняемые при деактивации плагина
     */
    public function deactivationPlugin(): void
    {
        remove_shortcode('viewBuyButton');
    }

    /**
     * Добавление опций в базу Wordpress при активации
     *
     * @return void
     */
    public function addOptions(): void
    {
        foreach ($this->optionsPull as $keyOption => $defaultValue) {
            add_option($keyOption, $defaultValue);
        }
        PluginUpdate::createOrderTable();
    }

    /**
     * Меню или суб меню плагина
     *
     * @return void
     */
    public function adminOptions(): void
    {
        //Подключается если есть менюя от Woocommerce
        $page_option = add_submenu_page(
            'woocommerce',
            self::NAME_SUB_MENU,
            self::NAME_SUB_MENU,
            'manage_woocommerce',
            self::URL_SUB_MENU,
            [$this, 'showSettingPage']
        );
        add_action('admin_print_styles-' . $page_option, array($this, 'styleAddPage')); //загружаем стили только для страницы плагина
        add_action('admin_print_scripts-' . $page_option, array($this, 'scriptAddPage')); //Скрипты
    }

    /**
     * Стили для страницы плагина
     *
     * @return void
     */
    public function styleAddPage(): void
    {
        wp_register_style('buybootstrapcss1', plugins_url() . '/' . self::PATCH_PLUGIN . '/' . 'bootstrap/css/bootstrap.css');
        wp_enqueue_style('buybootstrapcss1');
        wp_register_style('buyadmincss2', plugins_url() . '/' . self::PATCH_PLUGIN . '/' . 'css/admin.css');
        wp_enqueue_style('buyadmincss2');
    }

    /**
     * Скрипты для страницы плагина
     *
     * @return void
     */
    public function scriptAddPage(): void
    {
        wp_enqueue_script(
            'buybootstrapjs1',
            plugins_url() . '/' . self::PATCH_PLUGIN . '/' . 'bootstrap/js/bootstrap.js',
            ['jquery'],
            self::VERSION
        );
        wp_enqueue_script(
            'buyorder',
            plugins_url() . '/' . self::PATCH_PLUGIN . '/' . 'js/admin_order.js',
            ['jquery'],
            self::VERSION
        );
        wp_localize_script(
            'buyorder',
            'buyadminnonce',
            [
                'url' => admin_url(plugins_url() . '/' . self::PATCH_PLUGIN . '/' . 'js/admin_order.js'),
                'nonce' => wp_create_nonce('superKey')
            ]
        );
        wp_enqueue_script(
            'form-builder',
            sprintf('%s/%s/js/formBuilder/form-builder.min.js',
                plugins_url(),
                self::PATCH_PLUGIN),
            ['jquery'],
            self::VERSION
        );
        wp_enqueue_script(
            'form-builder',
            sprintf('%s/%s/js/formBuilder/form-render.min.js',
                plugins_url(),
                self::PATCH_PLUGIN),
            ['jquery'],
            self::VERSION
        );
    }

    /**
     * Стили для фронтэнда
     *
     * @return void
     */
    public function styleAddFrontPage(): void
    {
        foreach ($this->getStylesFront() as $styleName => $styleParams) {
            wp_register_style($styleName, $styleParams['url'], $styleParams['deps']);
            wp_enqueue_style($styleName);
        }
    }

    /**
     * Стили для фронта
     *
     * @return array<string, mixed>[url,path,deps]
     */
    public function getStylesFront(): array
    {
        $numForm = $this->commonOptions->getFormStyle();
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
    public function scriptAddFrontPage(): void
    {
        wp_enqueue_script(
            'buy-one-click-yandex-metrica',
            sprintf('%s/%s/js/BuyOneClickYandexMetrica.js', plugins_url(), self::PATCH_PLUGIN),
            ['jquery'],
            self::VERSION
        );
        wp_enqueue_script('buyonclickfrontjs', plugins_url() . '/' . self::PATCH_PLUGIN . '/' . 'js/form.js', ['jquery', 'buymaskedinput'], self::VERSION);
        wp_enqueue_script('buymaskedinput', plugins_url() . '/' . self::PATCH_PLUGIN . '/' . 'js/jquery.maskedinput.min.js', ['jquery'], self::VERSION);
    }

    /**
     * Страница плагина
     */
    public function showSettingPage()
    {
        include_once WP_PLUGIN_DIR . '/' . self::PATCH_PLUGIN . '/' . self::OPTIONS_NAME_PAGE;
    }

    /**
     * Активная вкладка в админпанели плагина
     * @return string css Класс для активной вкладки
     */
    public function adminActiveTab($tab_name = null, $tab = null)
    {
        if (isset($_GET['tab']) && !$tab) {
            $tab = $_GET['tab'];
        } else {
            $tab = 'general';
        }

        $output = '';
        if (isset($tab_name) && $tab_name) {
            if ($tab_name == $tab) {
                $output = ' nav-tab-active';
            }
        }
        echo $output;
    }

    /**
     * Показывает нужную страницу исходя из вкладки на страницы настроек плагина
     * @result include_once tab{номер вкладки}-option1.php
     *
     * @return void
     */
    public function showPage(): void
    {
        $pages = $this->getTabs();
        $tab = $_GET['tab'] ?? 'default';
        if (array_key_exists($tab, $pages) && file_exists($pages[$tab])) {
            include_once $pages[$tab];
        }
    }
    
    /**
     * Табы страницы настроек
     *
     * @return array<string, string>
     */
    private function getTabs(): array
    {
        $path = WP_PLUGIN_DIR.DIRECTORY_SEPARATOR.self::PATCH_PLUGIN.DIRECTORY_SEPARATOR.'page';
        return [
            'default' => sprintf('%s/tab1-option1.php', $path),
            'general' => sprintf('%s/tab1-option1.php', $path),
            'notification' => sprintf('%s/tab2-option1.php', $path),
            'orders' => sprintf('%s/tab3-option1.php', $path),
            'help' => sprintf('%s/tab4-option1.php', $path),
            'marketing' => sprintf('%s/tab5-option1.php', $path),
            'design_form' => sprintf('%s/tab6-option1.php', $path),
        ];
    }

    /**
     * Добавляет пункт настроек на странице активированных плагинов
     *
     * @param array<int, string> $commonMenu
     * @param string $filePath
     * @return array<int, string>
     */
    public function pluginLinkSetting(array $commonMenu, string $filePath): array
    {
        $pluginPath = self::PATCH_PLUGIN . '/' . self::INDEX_NAME_FILE;
        if ($filePath === $pluginPath) {
            $listLinks = [
                sprintf('<a href="admin.php?page=%s">%s</a>', self::URL_SUB_MENU, __("Settings", "default")),
                sprintf('<a href="https://t.me/coderunphp">%s</a>',  __("Telegram", "coderun-oneclickwoo")),
            ];
            $commonMenu = array_merge($commonMenu, $listLinks);
        }
        return $commonMenu;
    }

    /**
     * Вернёт нужную настройку
     * @param        $key Ключ опции относящийся к $optionsBush
     * @param string $optionsBush раздел настроек
     * @param string $defaultValue значение по умолчанию, если нет опции
     *
     * @return mixed|string
     * @throws Exception
     */
    public function getOption($key, $optionsBush = 'buyoptions', $defaultValue = '')
    {
        if (!array_key_exists($optionsBush, $this->optionsPull) || empty($optionsBush)) {
            throw new Exception(sprintf('Invalid settings key: %s', $optionsBush));
        }

        if (empty($this->optionsPull[$optionsBush])) {
            $this->optionsPull[$optionsBush] = \get_option($optionsBush, []);
        }

        if (isset($this->optionsPull[$optionsBush][$key])) {
            return $this->optionsPull[$optionsBush][$key];
        }
        return $defaultValue;
    }

    /**
     * Указываем WordPress опции с которыми работает плагин
     */
    public function registeringSettings()
    {
        // Tab6
        \register_setting(\sprintf('%s_options', self::OPTIONS_DESIGN_FORM), self::OPTIONS_DESIGN_FORM, [
            'type'              => 'array',
            'group'             => \sprintf('%s_options', self::OPTIONS_DESIGN_FORM),
            'description'       => '',
            'sanitize_callback' => function ($forms) {
                if (\is_array($forms)) {
                    foreach ($forms as $key => $value) {
                        $forms[$key] = \trim($value);
                    }
                }
                return $forms;
            },
            'show_in_rest'      => false,
            'default' => [],
        ]);

        // Tab5
        \register_setting(sprintf('%s_options', self::OPTIONS_MARKETING), self::OPTIONS_MARKETING, [
            'type'              => 'array',
            'group'             => sprintf('%s_options', self::OPTIONS_MARKETING),
            'description'       => '',
            'sanitize_callback' => function ($forms) {
                if (\is_array($forms)) {
                    foreach ($forms as $key => $value) {
                        $forms[$key] = \trim($value);
                    }
                }
                return $forms;
            },
            'show_in_rest'      => false,
            'default' => [],
        ]);
        // Tab1
        \register_setting(sprintf('%s_options', self::OPTIONS_GENERAL), self::OPTIONS_GENERAL, [
            'type'              => 'array',
            'group'             => sprintf('%s_options', self::OPTIONS_GENERAL),
            'description'       => '',
            'sanitize_callback' => function ($forms) {
                return $forms;
            },
            'show_in_rest'      => false,
            'default' => [],
        ]);
        // Tab2 - Уведомления
        \register_setting(sprintf('%s_options', self::OPTIONS_NOTIFICATIONS), self::OPTIONS_NOTIFICATIONS, [
            'type'              => 'array',
            'group'             => sprintf('%s_options', self::OPTIONS_NOTIFICATIONS),
            'description'       => '',
            'sanitize_callback' => function ($forms) {
                return $forms;
            },
            'show_in_rest'      => false,
            'default' => [],
        ]);
    }

    /**
     * @return GeneralOptions
     */
    public function getCommonOptions(): GeneralOptions
    {
        return $this->commonOptions;
    }

    /**
     * @return NotificationOptions
     */
    public function getNotificationOptions(): NotificationOptions
    {
        return $this->notificationOptions;
    }
}
