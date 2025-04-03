<?php

namespace Coderun\BuyOneClick;

use Coderun\BuyOneClick\Common\ObjectWithConstantState;
use Coderun\BuyOneClick\Constant\Pages;
use Coderun\BuyOneClick\Constant\ShortCodes as ShortCodesConst;
use Coderun\BuyOneClick\Constant\TranslationString;
use Coderun\BuyOneClick\Controller\Factory\AdminControllerFactory;
use Coderun\BuyOneClick\Controller\Factory\CartControllerFactory;
use Coderun\BuyOneClick\Controller\Factory\FormControllerFactory;
use Coderun\BuyOneClick\Controller\Factory\OrderControllerFactory;
use Coderun\BuyOneClick\Options\General as GeneralOptions;
use Coderun\BuyOneClick\Options\Notification as NotificationOptions;
use Coderun\BuyOneClick\Options\Marketing as MarketingOptions;
use Coderun\BuyOneClick\Service\Factory\ButtonFactory as ButtonServiceFactory;
use Coderun\BuyOneClick\Service\Factory\EmailTemplateFactory;
use Coderun\BuyOneClick\Service\Factory\ShortCodesFactory;
use Coderun\BuyOneClick\Utils\Hooks;
use Coderun\BuyOneClick\Utils\Translation;
use Exception;
use WC_Product;
use Coderun\BuyOneClick\Constant\Options\Type as OptionsType;

use function array_key_exists;
use function file_exists;
use function is_array;
use function method_exists;
use function get_option;
use function register_setting;

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
     * со значением по умолчанию
     *
     * @var array<string, array>
     */
    protected array $optionsPull = [
        self::OPTIONS_GENERAL       => [],
        self::OPTIONS_NOTIFICATIONS => [],
        self::OPTIONS_MARKETING     => [],
        self::OPTIONS_DESIGN_FORM   => [],
    ];

    /**
     * Singletone
     *
     * @return Core
     */
    public static function getInstance(): Core
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Точка входа
     *
     * @return void
     * @throws Exception
     */
    public function initializingPlugin(): void
    {
        // Инициализация настроек
        add_action(
            'init',
            function (): void {
                $this->commonOptions = new GeneralOptions(get_option(OptionsType::GENERAL, []));
                $this->notificationOptions = new NotificationOptions(get_option(OptionsType::NOTIFICATIONS, []));
                $this->marketingOptions = new MarketingOptions(get_option(OptionsType::MARKETING, []));
            }
        );
        add_action(
            'init',
            [Translation::class, 'registrationTranslate']
        );
        add_action(
            'init',
            function (): void {
                Translation::registrationTranslateByOptions($this->commonOptions);
            }
        );
        add_action(
            'init',
            function (): void {
                do_action('buy_one_click_woocommerce_start_load_core');
                ObjectWithConstantState::getInstance();
            }
        );
        add_action(
            'init',
            function (): void {
                $this->initAction();
            }
        );
        add_action(
            'admin_init',
            function (): void {
                $this->registeringSettings();
            }
        );
        // Инициализация допустимых настроек
        add_action(
            'init',
            static function (): void {
                (new ShortCodesFactory())->create();
            }
        );

        add_action(
            'woocommerce_email_before_order_table',
            static function ($order, $sent_to_admin, $plain_text): void {
                echo (new EmailTemplateFactory())->create()->modificationOrderTemplateWooCommerce($order);
            },
            10,
            3
        );
        add_action(
            'wp_head',
            function (): void {
                $this->frontVariables();
            }
        );
        // Обработчики запросов
        $this->initController();
        $this->initAdminPages();

        // Плагин загружен
        add_action(
            'init',
            static function (): void {
                Hooks::load();
            }
        );
        add_filter('gettext', function ($translation, $text, $domain) {
            if ($domain !== 'coderun-oneclickwoo' || !function_exists('pll__')) {
                return $translation;
            }
            if (!in_array($text, TranslationString::all())) {
                return $translation;
            }
            return Translation::translate($text);
        }, 10, 3);
    }

    /**
     * Контроллеры
     *
     * @return void
     */
    protected function initController(): void
    {
        add_action(
            'init',
            static function (): void {
                ((new OrderControllerFactory())->create())->init();
            }
        );
        add_action(
            'init',
            static function (): void {
                ((new FormControllerFactory())->create())->init();
            }
        );
        add_action(
            'init',
            static function (): void {
                ((new CartControllerFactory())->create())->init();
            }
        );
        add_action(
            'init',
            static function () {
                ((new AdminControllerFactory())->create())->init();
            }
        );
    }

    /**
     * Инициализация основного функционала
     * Зацеп для отрисовки кнопок
     *
     * @return void
     * @throws Exception
     */
    protected function initAction(): void
    {
        if ($this->commonOptions->isEnableButton()) {
            $locationInProductCard = $this->commonOptions->getPositionButton(); //Позиция кнопки
            add_action($locationInProductCard, [$this, 'styleAddFrontPage']); //Стили фронта
            add_action($locationInProductCard, [$this, 'scriptAddFrontPage']); //Скрипты фронта
            add_action(
                $locationInProductCard,
                static function (): void {
                    echo((new ButtonServiceFactory())->create())->getHtmlOrderButtons();
                }
            ); //Кнопка заказать
            //Положение в категории товаров
            if ($this->commonOptions->isEnableButtonCategory()) {
                $locationInCategory = $this->commonOptions->getButtonPositionInCategory(); //Позиция кнопки
                add_action(
                    $locationInCategory,
                    static function (): void {
                        echo((new ButtonServiceFactory())->create())->getHtmlOrderButtons();
                    }
                ); //Кнопка заказать
                add_action($locationInCategory, [$this, 'styleAddFrontPage']); //Стили фронта
                add_action($locationInCategory, [$this, 'scriptAddFrontPage']); //Скрипты фронта
            }
        }
        // Для товаров которых нет в наличие
        add_filter(
            'woocommerce_get_stock_html',
            function ($html) {
                if ($this->commonOptions->isEnableButton() && strlen($this->commonOptions->getPositionButtonOutStock()) < 5) {
                    return;
                }
                global $product;
                if ($product instanceof WC_Product && method_exists('WC_Product', 'get_availability')) {
                    $availability = $product->get_availability();
                    // Товар имеет статус не в наличие
                    if (strlen($html) > 1 && isset($availability['class']) && $availability['class'] === 'out-of-stock') {
                        if (!$product->is_type('variable')) { // Не показывать в вариативных, Woo по умолчанию оставляет обычную кнопку
                            $this->styleAddFrontPage();
                            $this->scriptAddFrontPage();
                            $html .= ((new ButtonServiceFactory())->create())->getHtmlOrderButtons();
                        }
                    }
                }
                return $html;
            }
        );
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
    protected function frontVariables(): void
    {
        $variables = ['ajaxurl' => admin_url('admin-ajax.php')];
        $variables['variation'] = 0;
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
            $variables['after_message_form'] = Translation::translate($this->commonOptions->getSubmittingFormMessageSuccess());
        }
        if ($this->marketingOptions->getAfterClickingOnButton()) {
            $variables['callback_after_clicking_on_button'] = $this->marketingOptions->getAfterClickingOnButton();
        }
        if ($this->marketingOptions->getSuccessfulFormSubmission()) {
            $variables['callback_successful_form_submission'] = $this->marketingOptions->getSuccessfulFormSubmission();
        }
        $variables['yandex_metrica'] = [
            'transfer_data_to_yandex_commerce' => $this->marketingOptions->isTransferDataToYandexCommerce(),
            'data_layer'                       => $this->marketingOptions->getNameOfYandexMetricaDataContainer(),
            'goal_id'                          => $this->marketingOptions->getGoalIdInYandexECommerce(),
        ];
        $variables['add_an_order_to_woo_commerce'] = $this->commonOptions->isAddAnOrderToWooCommerce();
        $variables = Hooks::filterInitFrontVariables($variables);
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
        foreach (ShortCodesConst::all() as $code) {
            remove_shortcode($code);
        }
    }

    /**
     * Добавление опций в базу Wordpress при активации
     *
     * @return void
     */
    public function activationPlugin(): void
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
            static function (): void {
                include_once WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . self::PATCH_PLUGIN . DIRECTORY_SEPARATOR . self::OPTIONS_NAME_PAGE;
            }
        );
        add_action('admin_print_styles-' . $page_option, [$this, 'styleAddPage']); //загружаем стили только для страницы плагина
        add_action('admin_print_scripts-' . $page_option, [$this, 'scriptAddPage']); //Скрипты
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
                'url'   => admin_url(plugins_url() . '/' . self::PATCH_PLUGIN . '/' . 'js/admin_order.js'),
                'nonce' => wp_create_nonce('superKey'),
            ]
        );
        wp_enqueue_script(
            'form-builder',
            sprintf(
                '%s/%s/js/formBuilder/form-builder.min.js',
                plugins_url(),
                self::PATCH_PLUGIN
            ),
            ['jquery'],
            self::VERSION
        );
        wp_enqueue_script(
            'form-builder',
            sprintf(
                '%s/%s/js/formBuilder/form-render.min.js',
                plugins_url(),
                self::PATCH_PLUGIN
            ),
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
                'url'  => $wp_uploads_dir['baseurl'] . '/' . self::PATCH_PLUGIN . '/css/general.css',
                'path' => $wp_uploads_dir['basedir'] . '/' . self::PATCH_PLUGIN . '/css/general.css',
                'deps' => [],
            ];
        } elseif (file_exists(get_stylesheet_directory() . '/' . self::PATCH_PLUGIN . '/css/general.css')) {
            $styles['buyonclickfront-general'] = [
                'url'  => get_stylesheet_directory_uri() . '/' . self::PATCH_PLUGIN . '/css/general.css',
                'path' => get_stylesheet_directory() . '/' . self::PATCH_PLUGIN . '/css/general.css',
                'deps' => [],
            ];
        } else {
            $styles['buyonclickfront-general'] = [
                'url'  => plugins_url() . '/' . self::PATCH_PLUGIN . '/templates/css/general.css',
                'path' => CODERUN_ONECLICKWOO_PLUGIN_DIR . '/templates/css/general.css',
                'deps' => [],
            ];
        }


        if (file_exists($wp_uploads_dir['basedir'] . '/' . self::PATCH_PLUGIN . '/css/form_' . $numForm . '.css')) {
            $styles['buyonclickcss2'] = [
                'url'  => $wp_uploads_dir['baseurl'] . '/' . self::PATCH_PLUGIN . '/css/form_' . $numForm . '.css',
                'path' => $wp_uploads_dir['basedir'] . '/' . self::PATCH_PLUGIN . '/css/form_' . $numForm . '.css',
                'deps' => ['buyonclickfront-general'],
            ];
        } elseif (file_exists(get_stylesheet_directory() . '/' . self::PATCH_PLUGIN . '/css/form_' . $numForm . '.css')) {
            $styles['buyonclickcss2'] = [
                'url'  => get_stylesheet_directory_uri() . '/' . self::PATCH_PLUGIN . '/css/form_' . $numForm . '.css',
                'path' => get_stylesheet_directory() . '/' . self::PATCH_PLUGIN . '/css/form_' . $numForm . '.css',
                'deps' => ['buyonclickfront-general'],
            ];
        } else {
            $styles['buyonclickcss2'] = [
                'url'  => plugins_url() . '/' . self::PATCH_PLUGIN . '/templates/css/form_' . $numForm . '.css',
                'path' => CODERUN_ONECLICKWOO_PLUGIN_DIR . '/templates/css/form_' . $numForm . '.css',
                'deps' => ['buyonclickfront-general'],
            ];
        }

        if (file_exists($wp_uploads_dir['basedir'] . '/' . self::PATCH_PLUGIN . '/css/formmessage.css')) {
            $styles['buyonclickfrontcss3'] = [
                'url'  => $wp_uploads_dir['baseurl'] . '/' . self::PATCH_PLUGIN . '/css/formmessage.css',
                'path' => $wp_uploads_dir['basedir'] . '/' . self::PATCH_PLUGIN . '/css/formmessage.css',
                'deps' => ['buyonclickfront-general'],
            ];
        } elseif (file_exists(get_stylesheet_directory() . '/' . self::PATCH_PLUGIN . '/css/formmessage.css')) {
            $styles['buyonclickfrontcss3'] = [
                'url'  => get_stylesheet_directory_uri() . '/' . self::PATCH_PLUGIN . '/css/formmessage.css',
                'path' => get_stylesheet_directory() . '/' . self::PATCH_PLUGIN . '/css/formmessage.css',
                'deps' => ['buyonclickfront-general'],
            ];
        } else {
            $styles['buyonclickfrontcss3'] = [
                'url'  => plugins_url() . '/' . self::PATCH_PLUGIN . '/templates/css/formmessage.css',
                'path' => CODERUN_ONECLICKWOO_PLUGIN_DIR . '/templates/css/formmessage.css',
                'deps' => ['buyonclickfront-general'],
            ];
        }

        $styles['loading'] = [
            'url'  => plugins_url() . '/' . self::PATCH_PLUGIN . '/css/loading-btn/loading.css',
            'path' => CODERUN_ONECLICKWOO_PLUGIN_DIR . '/css/loading-btn/loading.css',
            'deps' => [],
        ];
        $styles['loading-btn'] = [
            'url'  => plugins_url() . '/' . self::PATCH_PLUGIN . '/css/loading-btn/loading-btn.css',
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
     * Стиль активной вкладки
     *
     * @param string $tabName
     *
     * @return string
     */
    public function getCssOfActiveTab(string $tabName): string
    {
        $currentTab = $_GET['tab'] ?? Pages::GENERAL;
        return $tabName === $currentTab ? 'nav-tab-active' : '';
    }

    /**
     * Показывает нужную страницу исходя из вкладки на страницы настроек плагина
     *
     * @result include_once tab{номер вкладки}-option1.php
     *
     * @return void
     */
    public function showPage(): void
    {
        $pages = $this->getTabs();
        $tab = $_GET['tab'] ?? Pages::DEFAULT;
        if (array_key_exists($tab, $pages) && file_exists($pages[$tab])) {
            include_once $pages[$tab];
            return;
        }
        include_once $pages[Pages::DEFAULT];
    }

    /**
     * Табы страницы настроек
     *
     * @return array<string, string>
     */
    private function getTabs(): array
    {
        $path = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . self::PATCH_PLUGIN . DIRECTORY_SEPARATOR . 'page';
        return [
            Pages::DEFAULT      => sprintf('%s/tab1-option1.php', $path),
            Pages::GENERAL      => sprintf('%s/tab1-option1.php', $path),
            Pages::NOTIFICATION => sprintf('%s/tab2-option1.php', $path),
            Pages::ORDERS       => sprintf('%s/tab3-option1.php', $path),
            Pages::MARKETING    => sprintf('%s/tab5-option1.php', $path),
            Pages::DESIGN_FORM  => sprintf('%s/tab6-option1.php', $path),
        ];
    }

    /**
     * Добавляет пункт настроек на странице активированных плагинов
     *
     * @param  array<int, string> $commonMenu
     * @param  string             $filePath
     * @return array<int, string>
     */
    public function pluginLinkSetting(array $commonMenu, string $filePath): array
    {
        $pluginPath = self::PATCH_PLUGIN . '/' . self::INDEX_NAME_FILE;
        if ($filePath === $pluginPath) {
            $listLinks = [
                sprintf('<a href="admin.php?page=%s">%s</a>', self::URL_SUB_MENU, __('Settings', 'default')),
                sprintf('<a href="https://t.me/coderunphp">%s</a>', __('Telegram', 'coderun-oneclickwoo')),
            ];
            $commonMenu = array_merge($commonMenu, $listLinks);
        }
        return $commonMenu;
    }

    /**
     * Вернёт нужную настройку
     *
     * @param $key          Ключ опции
     *                      относящийся к
     *                      $optionsBush
     * @param string                           $optionsBush  раздел
     *                                                       настроек
     * @param string                           $defaultValue значение по умолчанию, если нет опции
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
    protected function registeringSettings()
    {
        // Tab6
        register_setting(
            \sprintf('%s_options', self::OPTIONS_DESIGN_FORM),
            self::OPTIONS_DESIGN_FORM,
            [
                'type'              => 'array',
                'group'             => \sprintf('%s_options', self::OPTIONS_DESIGN_FORM),
                'description'       => '',
                'sanitize_callback' => function ($forms) {
                    if (is_array($forms)) {
                        foreach ($forms as $key => $value) {
                            $forms[$key] = \trim($value);
                        }
                    }
                    return $forms;
                },
                'show_in_rest'      => false,
                'default'           => [],
            ]
        );

        // Tab5
        register_setting(
            sprintf('%s_options', self::OPTIONS_MARKETING),
            self::OPTIONS_MARKETING,
            [
                'type'              => 'array',
                'group'             => sprintf('%s_options', self::OPTIONS_MARKETING),
                'description'       => '',
                'sanitize_callback' => function ($forms) {
                    if (is_array($forms)) {
                        foreach ($forms as $key => $value) {
                            $forms[$key] = \trim($value);
                        }
                    }
                    return $forms;
                },
                'show_in_rest'      => false,
                'default'           => [],
            ]
        );
        // Tab1
        register_setting(
            sprintf('%s_options', self::OPTIONS_GENERAL),
            self::OPTIONS_GENERAL,
            [
                'type'              => 'array',
                'group'             => sprintf('%s_options', self::OPTIONS_GENERAL),
                'description'       => '',
                'sanitize_callback' => function ($forms) {
                    return $forms;
                },
                'show_in_rest'      => false,
                'default'           => [],
            ]
        );
        // Tab2 - Уведомления
        register_setting(
            sprintf('%s_options', self::OPTIONS_NOTIFICATIONS),
            self::OPTIONS_NOTIFICATIONS,
            [
                'type'              => 'array',
                'group'             => sprintf('%s_options', self::OPTIONS_NOTIFICATIONS),
                'description'       => '',
                'sanitize_callback' => function ($forms) {
                    return $forms;
                },
                'show_in_rest'      => false,
                'default'           => [],
            ]
        );
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

    /**
     * @return MarketingOptions
     */
    public function getMarketingOptions(): MarketingOptions
    {
        return $this->marketingOptions;
    }
}
