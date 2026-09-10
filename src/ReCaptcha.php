<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick;

class ReCaptcha
{
    protected static ?ReCaptcha $_instance = null;

    /**
     * Вывод капчи
     *
     * @param string $plugin
     */
    public function view(string $plugin): void
    {
        if ($plugin === 'advanced_nocaptcha_recaptcha') {
            $this->viewPluginAnrCaptcha();
        }
    }

    /**
     * Провека капчи
     *
     * @param string $plugin
     *
     * @return array{check: bool, message: string}
     */
    public function check(string $plugin): array
    {
        $result = [
            'check'   => false,
            'message' => esc_html_e('Captcha failed', 'buy-one-click-woocommerce'),
        ];
        if ($plugin === 'advanced_nocaptcha_recaptcha') {
            if (!$this->isSupportPluginAnrCaptcha()) {
                return $result;
            }
            $result['check'] = (bool) \anr_verify_captcha();
            $result['message'] = \anr_get_option('error_message', '');
        }
        ob_end_clean();
        return $result;
    }

    /**
     * Поддерживаемые плагины
     * Верёнт многомерный ассоциативный массив
     * name - Имя плагина
     * url - страница в WP
     * contributors - Автор в репозитарии WP
     *
     * @return array<string, array{name: string, url: string, contributors: string}>
     */
    public function isSupported(): array
    {
        $result = [];


        $result['advanced_nocaptcha_recaptcha'] = [
            'name'         => 'Advanced noCaptcha & invisible Captcha (v2 & v3)',
            'url'          => 'https://ru.wordpress.org/plugins/advanced-nocaptcha-recaptcha/',
            'contributors' => 'shamim51',
        ];


        return $result;
    }

    /**
     * Выводи необходимы данные в нужное место если плагин есть
     * Plugin: Advanced noCaptcha & invisible Captcha (v2 & v3)
     * Contributors: shamim51
     * Url: https://ru.wordpress.org/plugins/advanced-nocaptcha-recaptcha/
     */
    protected function viewPluginAnrCaptcha(): void
    {
        if (!$this->isSupportPluginAnrCaptcha()) {
            return;
        }

        $version = \anr_get_option('captcha_version', 'v2_checkbox');

        if ('v2_checkbox' === $version) {
            \anr_captcha_class::init()->v2_checkbox_script();
        } elseif ('v2_invisible' === $version) {
            \anr_captcha_class::init()->v2_invisible_script();
        } elseif ('v3' === $version) {
            \anr_captcha_class::init()->v3_script();
        }

        \anr_captcha_class::init()->form_field();
    }

    protected function isSupportPluginAnrCaptcha(): bool
    {
        if (!function_exists('\anr_get_option')) {
            return false;
        }
        if (!function_exists('\anr_verify_captcha')) {
            return false;
        }
        if (!class_exists('\anr_captcha_class')) {
            return false;
        }
        if (!method_exists('\anr_captcha_class', 'init')) {
            return false;
        }
        if (!method_exists('\anr_captcha_class', 'v2_checkbox_script')) {
            return false;
        }
        if (!method_exists('\anr_captcha_class', 'v2_invisible_script')) {
            return false;
        }
        if (!method_exists('\anr_captcha_class', 'v3_script')) {
            return false;
        }
        if (!method_exists('\anr_captcha_class', 'form_field')) {
            return false;
        }

        return true;
    }

    /**
     * Singletone
     *
     * @return self
     */
    public static function getInstance(): self
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    protected function __construct()
    {
    }

    public function __clone(): void
    {
        throw new \Exception('Forbiden instance __clone');
    }

    public function __wakeup(): void
    {
        throw new \Exception('Forbiden instance __wakeup');
    }
}
