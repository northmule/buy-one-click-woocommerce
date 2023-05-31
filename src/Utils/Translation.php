<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Utils;

use Coderun\BuyOneClick\Constant\TranslationString;

use Coderun\BuyOneClick\Options\General as GeneralOptions;

use function function_exists;

/**
 * Class Translation
 *
 * @package Coderun\BuyOneClick\Utils
 */
class Translation
{
    
    /**
     * @param string $text
     *
     * @return string
     */
    public static function translate(string $text = '' ): string
    {
        if ($text === '') {
            return $text;
        }
        // Polylang Translation
        if (function_exists( '\pll__') ) {
            $text = \pll__($text);
        }
        
        return $text;
        
    }
    
    /**
     * Регистрация переводов для плагинов
     *
     * @return void
     */
    public static function registrationTranslate(): void
    {
        if (function_exists('\pll_register_string')) {
            foreach (TranslationString::all() as $index => $sting) {
                \pll_register_string(sprintf('buy_one_click_%s', $index), $sting, 'buy-one-click-woocommerce', true);
            }
        }
    }
    
    /**
     * Регистрация переводов для настроек плагина(пользовательские настройки)
     *
     * @param GeneralOptions $options
     *
     * @return void
     */
    public static function registrationTranslateByOptions(GeneralOptions $options): void
    {
        if (function_exists('\pll_register_string')) {
            foreach ($options->getTextsForTranslation() as $string) {
                if (empty($string)) {
                    continue;
                }
                \pll_register_string($string, $string, 'buy-one-click-woocommerce', true);
            }
        }
    }
}