<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Service;

use Coderun\BuyOneClick\Core;
use Coderun\BuyOneClick\Options\General as GeneralOptions;
use Coderun\BuyOneClick\SimpleDataObjects\CustomOrderButton as CustomOrderButtonDataObject;
use Coderun\BuyOneClick\SimpleDataObjects\OrderButton as OrderButtonDataObject;
use Coderun\BuyOneClick\SimpleDataObjects\ShortcodeParameters;
use Coderun\BuyOneClick\Templates\OrderButton;
use Coderun\BuyOneClick\Utils\Hooks;
use Coderun\BuyOneClick\Utils\Product as ProductUtils;
use Coderun\BuyOneClick\Utils\Translation;
use Exception;

use function file_get_contents;

/**
 * Class Button
 *
 * @package Coderun\BuyOneClick\Service
 */
class Button
{
    /**
     * Настройки плагина
     *
     * @var GeneralOptions
     */
    protected GeneralOptions $commonOptions;

    /**
     * @param GeneralOptions $commonOptions
     */
    public function __construct(GeneralOptions $commonOptions)
    {
        $this->commonOptions = $commonOptions;
    }


    /**
     * Возвращает HTML кнопки "Купить в один клик"
     *
     * @param $params<int, mixed>
     *
     * @return string
     * @throws Exception
     */
    public function getHtmlOrderButtons($params = []): string
    {
        if ($this->commonOptions->getPositionButton()) {
            $name = $this->getButtonName();
            $productId = ProductUtils::getProductId();
            if (isset($params['id']) && !empty($params['id'])) {
                $productId = $params['id'];
            }
            if (empty($productId)) {
                return '';// ИД текущего товара не удалось узнать, покупать нечего
            }
            $scripts = '';
            $style = '';
            if ($this->commonOptions->isStyleInsertHtml()) {
                $scripts .= file_get_contents(sprintf('%s/js/form.js', CODERUN_ONECLICKWOO_PLUGIN_DIR));
                $scripts .= file_get_contents(sprintf('%s/js/jquery.maskedinput.min.js', CODERUN_ONECLICKWOO_PLUGIN_DIR));
                foreach (Core::getInstance()->getStylesFront() as $styleParam) {
                    if (!empty($styleParam['path']) && \file_exists($styleParam['path'])) {
                        $style .= file_get_contents($styleParam['path']);
                    }
                }
            }
            $this->initVariationAddon($productId);

            return (new OrderButton())->render(
                new OrderButtonDataObject(
                    [
                        'productId'    => $productId,
                        'buttonName'   => $name,
                        'variationId'  => 0,
                        'inlineStyle'  => $style,
                        'inlineScript' => $scripts,
                    ]
                )
            );
        }
        return '';
    }

    /**
     * HTML Кнопка заказа в один клик через Шорткод
     *
     * @param ShortcodeParameters $params
     *
     * @return string
     * @throws Exception
     */
    public function getHtmlOrderButtonsCustom(ShortcodeParameters $params): string
    {
        if ($this->commonOptions->getNameButton() and $this->commonOptions->getPositionButton()) {
            $this->initVariationAddon((int)$params->id);
            return (new OrderButton())->render(
                new CustomOrderButtonDataObject(
                    [
                        'productId'        => $params->id,
                        'productPrice'     => $params->price,
                        'productCount'     => $params->count,
                        'productName'      => $params->name,
                        'productPriceHtml' => $params->priceWithCurrency,
                        'buttonName'       => Translation::translate($this->commonOptions->getNameButton()),
                        'inlineStyle'      => '',
                        'inlineScript'     => '',
                    ]
                )
            );
        }

        return '';
    }

    /**
     * Формирует имя кнопки
     *
     * @return string
     */
    protected function getButtonName(): string
    {
        $defaultName = __('Buy on click', 'coderun-oneclickwoo');
        if (!$this->commonOptions->getNameButton()) {
            return $defaultName;
        }
        $name = '';
        $defaultName = $this->commonOptions->getNameButton();
        if ($this->commonOptions->getDescriptionOfPreOrderButton()) {
            $name = $this->commonOptions->getDescriptionOfPreOrderButton();
        }

        if (ProductUtils::getProductId() === 0 || !$this->commonOptions->isEnableWorkWithRemainingItems()) {
            return Translation::translate($defaultName);
        }
        $stockStatus = get_post_meta(ProductUtils::getProductId(), '_stock_status', true);
        //outofstock - нет в наличие
        //instock - в наличие
        //onbackorder - в не выполненом заказе

        return Translation::translate($stockStatus === 'outofstock' ? $name : $defaultName);
    }
    
    /**
     * Инициализация для дополнения с вариативными товарами
     *
     * @param int|string $productId
     *
     * @return void
     */
    protected function initVariationAddon($productId)
    {
        $product = wc_get_product($productId);
        if (!$product instanceof \WC_Product_Variable) {
            return;
        }
        Hooks::beforeDrawingOrderButtonOnlyForVariableProducts($this);
    }
}
