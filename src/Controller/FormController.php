<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Controller;

use Coderun\BuyOneClick\BuyFunction;

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
        $productId = intval($_POST['productid']);
        $variationId = intval($_POST['variation_selected']);

        if ($variationId > 0) {
            $productId = $variationId;
        }
        $form = BuyFunction::get_product_param($productId);
        $form['custom'] = 0;
        echo BuyFunction::viewBuyForm($form);
        die();
    }

    /**
     * Рисуте форму заказа (кастомную)
     *
     * @return void
     */
    public function viewFormOrderCustom()
    {
        echo BuyFunction::viewBuyForm([
            'article' =>  $_POST['productid'],
            'name' => $_POST['name'],
            'imageurl' => '',
            'amount' => $_POST['price'],
            'quantity' => $_POST['count'],
            'custom' => 1,
        ]);
        die();
    }
}
