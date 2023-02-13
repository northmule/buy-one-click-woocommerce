<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Service;

use Coderun\BuyOneClick\Constant\ShortcodeParameters;
use Coderun\BuyOneClick\Constant\ShortCodes as ShortCodesConst;
use Coderun\BuyOneClick\Options\General as GeneralOptions;
use Coderun\BuyOneClick\Service\Factory\ButtonFactory as ButtonServiceFactory;
use Coderun\BuyOneClick\Core;
use Coderun\BuyOneClick\Utils\Hooks;
use Exception;
use Coderun\BuyOneClick\SimpleDataObjects\ShortcodeParameters as ShortcodeParametersObjects;

use function ob_get_contents;
use function ob_start;
use function ob_end_clean;
use function shortcode_atts;
use function array_filter;
use function is_numeric;
use function wc_get_product;

class ShortCodes
{
    /**
     * Настройки плагина
     *
     * @var GeneralOptions
     */
    protected GeneralOptions $commonOptions;
    /**
     * @var array<string, string>
     */
    protected array $shortCodeFunctionMap = [
        ShortCodesConst::VIEW_BUY_BUTTON        => 'viewBuyButton',
        ShortCodesConst::VIEW_BUY_BUTTON_CUSTOM => 'viewBuyButtonCustom',
    ];

    /**
     * @param GeneralOptions $commonOptions
     */
    public function __construct(GeneralOptions $commonOptions)
    {
        $this->commonOptions = $commonOptions;

        foreach (ShortCodesConst::all() as $code) {
            if (!shortcode_exists($code)) {
                add_shortcode($code, [$this, $this->shortCodeFunctionMap[$code]]);
            }
        }
    }

    /**
     * Кластическая кнопка покупки в один клик.
     * Используется везде где можно получить ИД товара из Объекта WP.
     *
     * Дополнительные параметры шорткода. $params[id] - ИД товара WooCommerce
     *
     * @param array<string, int> $params
     *
     * @return string
     * @throws Exception
     */
    public function viewBuyButton($params): string
    {
        if (!$this->commonOptions->isEnableButtonShortcode()) {
            return '';
        }
        $params = array_filter(
            (array) $params,
            static function ($value, $key) {
                if (is_numeric($key)) {
                    return false;
                }
                if ($key === 'id') {
                    return is_numeric($value);
                }
                return true;
            },
            ARRAY_FILTER_USE_BOTH
        );
        $content = '';
        $params = shortcode_atts(['id' => 0], $params);
        $core = Core::getInstance();
        $core->styleAddFrontPage();
        $core->scriptAddFrontPage();
        if (!empty($params['id'])) {
            $content = $this->initVariationAddon($params['id']);
        }
        $content .= ((new ButtonServiceFactory())->create())->getHtmlOrderButtons($params);
        return $content;
    }

    /**
     *  Кнопка с возможностью передать параметры
     *
     *  id - код товара, name- наименование, count-количество,price- цена(число)
     *
     * @param array<string, mixed>|string $params
     *
     * @throws Exception
     */
    public function viewBuyButtonCustom(array $params): string
    {
        if (!$this->commonOptions->isEnableButtonShortcode()) {
            return '';
        }
        $params = array_filter((array) $params);
        $params = shortcode_atts(
            [
                ShortcodeParameters::PRODUCT_ID          => '1',
                ShortcodeParameters::PRODUCT_NAME        => 'noname',
                ShortcodeParameters::PRODUCT_COUNT       => '1',
                ShortcodeParameters::PRODUCT_PRICE       => '5',
                ShortcodeParameters::PRICE_WITH_CURRENCY => '5',
            ],
            $params
        );
        $core = Core::getInstance();
        $core->styleAddFrontPage();
        $core->scriptAddFrontPage();
        return ((new ButtonServiceFactory())->create())->getHtmlOrderButtonsCustom(
            new ShortcodeParametersObjects(
                [
                    'id'                => $params[ShortcodeParameters::PRODUCT_ID],
                    'name'              => $params[ShortcodeParameters::PRODUCT_NAME],
                    'count'             => $params[ShortcodeParameters::PRODUCT_COUNT],
                    'price'             => $params[ShortcodeParameters::PRODUCT_PRICE],
                    'priceWithCurrency' => $params[ShortcodeParameters::PRICE_WITH_CURRENCY],

                ]
            )
        );
    }
    
    /**
     * Инициализация для дополнения с вариативными товарами
     *
     * @param int|string $productId
     *
     * @return string
     */
    protected function initVariationAddon($productId): string
    {
        $product = wc_get_product($productId);
        if (!$product instanceof \WC_Product_Variable) {
            return '';
        }
        ob_start();
        Hooks::beforeDrawingOrderButtonOnlyForVariableProducts($this);
        $page = ob_get_contents();
        ob_end_clean();
        return $page;
    }
}
