<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Controller;

use Coderun\BuyOneClick\Help;
use Coderun\BuyOneClick\SimpleDataObjects\FieldsOfOrderForm;
use Coderun\BuyOneClick\Templates\Elements\Factory\FilesFactory;
use Coderun\BuyOneClick\Templates\Elements\Factory\QuantityFactory;
use Coderun\BuyOneClick\Templates\QuickOrderFormFactory;

use Coderun\BuyOneClick\Utils\Product as ProductUtils;

use function add_action;
use function intval;

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
     */
    public function viewFormOrder(): void
    {
        $productId = $_POST['productid'];
        $variationId = intval($_POST['variation_selected']);

        if ($variationId > 0) {
            $productId = $variationId;
        }
        $params = ProductUtils::getProductParam(intval($productId));
        $fields = new FieldsOfOrderForm([
            'productId' => $params['article'] ?? '',
            'productName' => $params['name'] ?? '',
            'productPrice' => $params['amount'] ?? '',
            'shortCode' => 0,
            'productImg' => $params['imageurl'] ?? '',
            'productSrcImg' => sprintf('<img src="%s" width="80" height="80">', $params['imageurl'] ?? ''),
            'variationPlugin' => Help::getInstance()->module_variation,
            'templateStyle' => $this->commonOptions->isStyleInsertHtml(),
            'formWithFiles' => ((new FilesFactory())->create())->render(),
            'formWithQuantity' => ((new QuantityFactory())->create())->render()
        ]);
        
        echo ((new QuickOrderFormFactory())->create())->render($fields);
    }

    /**
     * Рисует форму заказа (из шорткода)
     *
     * @return void
     */
    public function viewFormOrderCustom()
    {
        $fields = new FieldsOfOrderForm([
            'productId' => $_POST['productid'] ?? '',
            'productName' => $_POST['name'] ?? '',
            'productPrice' => $_POST['price'] ?? '',
            'productCount' => $_POST['count'] ?? '',
            'shortCode' => 1,
            'productImg' => '',
            'productSrcImg' => '',
            'variationPlugin' => Help::getInstance()->module_variation,
            'templateStyle' => $this->commonOptions->isStyleInsertHtml(),
            'formWithFiles' => ((new FilesFactory())->create())->render(),
            'formWithQuantity' => ((new QuantityFactory())->create())->render()
        ]);
        echo ((new QuickOrderFormFactory())->create())->render($fields);
    }
}
