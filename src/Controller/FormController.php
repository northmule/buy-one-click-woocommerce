<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Controller;

use Coderun\BuyOneClick\Common\ObjectWithConstantState;
use Coderun\BuyOneClick\SimpleDataObjects\FieldsOfOrderForm;
use Coderun\BuyOneClick\SimpleDataObjects\Product;
use Coderun\BuyOneClick\Templates\Elements\Factory\FilesFactory;
use Coderun\BuyOneClick\Templates\Elements\Factory\QuantityFactory;
use Coderun\BuyOneClick\Templates\QuickOrderFormFactory;
use Coderun\BuyOneClick\Utils\Product as ProductUtils;

use function add_action;
use function intval;
use function sprintf;

/**
 * Class FormController
 *
 * @package Coderun\BuyOneClick\Controller
 */
class FormController extends Controller
{
    /**
     * @inheritDoc
     *
     * @return void
     */
    public function init(): void
    {
        add_action(
            'wp_ajax_getViewForm',
            [$this, 'viewFormOrder']
        );
        add_action(
            'wp_ajax_nopriv_getViewForm',
            [$this, 'viewFormOrder']
        );

        add_action(
            'wp_ajax_getViewFormCustom',
            [$this, 'viewFormOrderCustom']
        );
        add_action(
            'wp_ajax_nopriv_getViewFormCustom',
            [$this, 'viewFormOrderCustom']
        );
    }

    /**
     * Форма заказа
     *
     * @return void
     * @throws \Exception
     */
    public function viewFormOrder(): void
    {
        if (!wp_verify_nonce($this->getFrontendNonce(), self::FRONTEND_NONCE_ACTION)) {
            $this->abortOnFailedNonce();
        }
        $productId = isset($_POST['productid']) ? intval(wp_unslash($_POST['productid'])) : 0;
        $variationId = isset($_POST['variation_selected']) ? intval(wp_unslash($_POST['variation_selected'])) : 0;

        if ($variationId > 0) {
            $productId = $variationId;
        }
        $product = wc_get_product($productId);
        $productName = $product->get_name() ?? '';
        if ($product instanceof \WC_Product_Variation) {
            $productName .= ' ( ' . $product->get_attribute_summary() . ' ) ';
        }
        if (method_exists($product, 'get_image_id')) {
            $images = wp_get_attachment_image_src($product->get_image_id()); //Урл картинки товара
        }
        $productObject = new Product([
            'product' => $product,
        ]);
        $fields = new FieldsOfOrderForm(
            [
                'productId'        => $product->get_id() ?? '',
                'productName'      => $productName,
                'productPrice'     => ProductUtils::getProductPrice($product),
                'productPriceHtml' => $product->get_price_html() ?? '',
                'productCount'     => 1,
                'shortCode'        => 0,
                'productImg'       => $images[0] ?? '',
                'productSrcImg'    => sprintf('<img src="%s" width="80" height="80">', $images[0] ?? ''),
                'variationPlugin'  => ObjectWithConstantState::getInstance()->isVariations(),
                'templateStyle'    => $this->commonOptions->isStyleInsertHtml(),
                'formWithFiles'    => (new FilesFactory())->create()->render($productObject),
                'formWithQuantity' => (new QuantityFactory())->create()->render($productObject),
                'product'          => $product,
            ]
        );
        wp_send_json_success(((new QuickOrderFormFactory())->create())->render($fields));
    }

    /**
     * Рисует форму заказа (из шорткода)
     *
     * @return void
     * @throws \Exception
     */
    public function viewFormOrderCustom(): void
    {
        if (!wp_verify_nonce($this->getFrontendNonce(), self::FRONTEND_NONCE_ACTION)) {
            $this->abortOnFailedNonce();
        }
        $productObject = new Product([
            'product' => null,
        ]);
        $fields = new FieldsOfOrderForm(
            [
                'productId'        => isset($_POST['productid']) ? intval(wp_unslash($_POST['productid'])) : 0,
                'productName'      => isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '',
                'productPrice'     => isset($_POST['price']) ? floatval(wp_unslash($_POST['price'])) : 0,
                'productPriceHtml' => isset($_POST['priceHtml']) ? wp_kses_post(wp_unslash($_POST['priceHtml'])) : '',
                'productCount'     => isset($_POST['count']) ? intval(wp_unslash($_POST['count'])) : 1,
                'shortCode'        => 1,
                'productImg'       => '',
                'productSrcImg'    => '',
                'variationPlugin'  => ObjectWithConstantState::getInstance()->isVariations(),
                'templateStyle'    => $this->commonOptions->isStyleInsertHtml(),
                'formWithFiles'    => (new FilesFactory())->create()->render($productObject),
                'formWithQuantity' => (new QuantityFactory())->create()->render($productObject),
                'product'          => null,
            ]
        );
        wp_send_json_success(((new QuickOrderFormFactory())->create())->render($fields));
    }
}
