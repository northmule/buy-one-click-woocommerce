<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Service;

use Coderun\BuyOneClick\Common\ObjectWithConstantState;
use Coderun\BuyOneClick\Constant\ShortCodes as ShortCodesConst;
use Coderun\BuyOneClick\Options\General as GeneralOptions;
use Coderun\BuyOneClick\Service\Factory\ButtonFactory as ButtonServiceFactory;
use Coderun\BuyOneClick\Core;
use Exception;

use function shortcode_atts;

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
    protected $shortCodeFunctionMap = [
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
        $params = array_filter((array) $params);
        $content = '';
        $params = shortcode_atts(['id' => 0], $params);
        $core = Core::getInstance();
        $core->styleAddFrontPage();
        $core->scriptAddFrontPage();
        if (ObjectWithConstantState::getInstance()->isVariations()) {
            $content = \Coderun\BuyOneClick\VariationsAddition::getInstance()->shortCode();
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
                'id'    => '1',
                'name'  => 'noname',
                'count' => 1,
                'price' => 5,
            ],
            $params
        );
        $core = Core::getInstance();
        $core->styleAddFrontPage();
        $core->scriptAddFrontPage();
        return ((new ButtonServiceFactory())->create())->getHtmlOrderButtonsCustom($params);
    }
}
