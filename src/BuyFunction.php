<?php

namespace Coderun\BuyOneClick;

use Coderun\BuyOneClick\ValueObject\OrderForm;

/**
 * Некоторый функционал плагина
 *
 */
class BuyFunction
{
    /**
     * Собирает тело сообщения SMS
     * @param string $options Текст смс сообщения
     * @param OrderForm $orderForm
     *
     */
    public static function composeSms($options, OrderForm $orderForm)
    {
        return strtr($options, [
            '%FIO%' => $orderForm->getUserName(),
            '%FON%' => $orderForm->getUserPhone(),
            '%EMAIL%' => $orderForm->getUserEmail(),
            '%DOPINFO%' => $orderForm->getOrderAdminComment(),
            '%TPRICE%' => $orderForm->getProductPrice(),
            '%TNAME%' => $orderForm->getProductName(),
        ]);
    }

    /**
     * Форма для быстрого заказа
     */
    public static function viewBuyForm($params)
    {
        $default_params = array(
            'article' => '',
            'name' => '',
            'amount' => '',
            'custom' => '',
            'imageurl' => '',
            'imageurl' => '',
        );

        $params = array_merge($default_params, $params);

        $help = Help::getInstance();

        $options = $help->get_options();

        $field = array(
            'product_id' => $params['article'],
            'product_name' => $params['name'],
            'product_price' => $params['amount'],
            'form_custom' => $params['custom'] ? 1 : 0,
            'product_img' => $params['imageurl'],
            'product_src_img' => '<img src="' . $params['imageurl'] . '" width="80" height="80">',
            'variation_plugin' => $help->module_variation,
            'is_template_style' => self::is_template_style(),
            'html_form_file_upload' => self::get_from_upload_file(),
            'html_form_quantity' =>self::getQuantityForm()
        );


        ob_start();
        include_once CODERUN_ONECLICKWOO_TEMPLATES_PLUGIN_DIR . '/forms/order_form.php';
        $form = ob_get_contents();
        ob_end_clean();

        return apply_filters('coderun_oneclickwoo_order_form_html', $form);
    }

    /**
     * HTML форма кнопки "Заказать в один клик"
     * Кнопка работает с реальными товарами WooCommerce
     */
    public static function viewBuyButton($short_code = false, $params = [])
    {
        $page = '';
        if (Core::getInstance()->getOption('positionbutton', 'buyoptions')) {
            $name = self::get_button_name();
            $productId = self::getProductId();
            if (isset($params['id']) && !empty($params['id'])) {
                $productId = $params['id'];
            }
            if (empty($productId)) { // ИД текущего товара не удалось узнать, покупать нечего
                return;
            }
            $scripts = '';
            $style = '';
            if (Core::getInstance()->getOption('style_insert_html', 'buyoptions')) {
                $scripts .= \file_get_contents(sprintf('%s/js/form.js', CODERUN_ONECLICKWOO_PLUGIN_DIR));
                $scripts .= \file_get_contents(sprintf('%s/js/jquery.maskedinput.min.js', CODERUN_ONECLICKWOO_PLUGIN_DIR));
                foreach (Core::getInstance()->getStylesFront() as $styleName => $styleParam) {
                    if (!empty($styleParam['path']) && \file_exists($styleParam['path'])) {
                        $style .= \file_get_contents($styleParam['path']);
                    }
                }
            }
            if ($scripts) {
                $scripts = sprintf('<script>%s</script>', $scripts);
            }
            if ($style) {
                $style = sprintf('<style>%s</style>', $style);
            }
            ob_start(); ?>
            <?php if (strlen($name) > 0) { ?>
                <?php echo $scripts; ?>
                <?php echo $style; ?>
                <button
                        class="single_add_to_cart_button clickBuyButton button21 button alt ld-ext-left"
                        data-variation_id="0"
                        data-productid="<?php echo $productId; ?>">
                    <span> <?php echo $name; ?></span>
                    <div style="font-size:14px" class="ld ld-ring ld-cycle"></div>
                </button>
            <?php } ?>
            <?php
            $page = ob_get_contents();
            ob_end_clean();
        }

        if ($short_code) {
            return $page;
        } else {
            echo $page;
        }
    }

    public static function getProductId()
    {
        global $product;

        $product_id = 0;
        if ($product instanceof \WC_Product) {
            $product_id = $product->get_id();
        }

        return $product_id;
    }

    /**
     * HTML форма кнопки "Заказать в один клик" для произвольного способа заказа
     */
    public static function viewBuyButtonCustrom($arParams)
    {
        $page = '';

        $options = Help::getInstance()->get_options();

        if (!empty($options['buyoptions']['namebutton']) and ! empty($options['buyoptions']['positionbutton'])) {
            ob_start(); ?>

            <button
                    class="clickBuyButtonCustom button21 button alt ld-ext-left"
                    href="#" data-productid="<?php echo $arParams['id']; ?>"
                    data-name="<?php echo $arParams['name']; ?>"
                    data-count="<?php echo $arParams['count']; ?>"
                    data-price="<?php echo $arParams['price']; ?>">
                <span><?php echo $options['buyoptions']['namebutton']; ?></span>
                <div style="font-size:14px" class="ld ld-ring ld-cycle"></div>
            </button>
            
            <?php
            $page = ob_get_contents();
            ob_end_clean();
        }

        return $page;
    }

    /**
     * Вернёт форму загрузки файлов
     * @return type
     */
    protected static function get_from_upload_file()
    {
        $options = Help::getInstance()->get_options('buyoptions');
        if (!empty($options['upload_input_file_chek'])) {
            ob_start();
            include_once CODERUN_ONECLICKWOO_TEMPLATES_PLUGIN_DIR . '/forms/file_uploader.php';
            $form = ob_get_contents();
            ob_end_clean();

            return apply_filters('coderun_oneclickwoo_order_form_html', $form);
        }

        return '';
    }

    /**
     * Форма с количеством
     * @return string
     */
    protected static function getQuantityForm()
    {
        $options = Help::getInstance()->get_options('buyoptions');
        if (!empty($options['add_quantity_form'])) {
            ob_start();
            include_once CODERUN_ONECLICKWOO_TEMPLATES_PLUGIN_DIR . '/forms/quantity.php';
            $form = ob_get_contents();
            ob_end_clean();

            return apply_filters('coderun_oneclickwoo_quantity_form_html', $form);
        }

        return '';
    }


    protected static function is_template_style()
    {
        $options = Help::getInstance()->get_options();
        if (isset($options['buyoptions']['form_style_color']) && $options['buyoptions']['form_style_color'] == '6') {
            return true;
        }
        return false;
    }

    /**
     * Собираем информацию о товаре, для формы
     * Этот вариант кнопки расположен в карточке товара или в категории и подразумевает заказ 1й еденицы
     * товара (покупка в один клик, минуя корзину)
     * @return array 'article' - код товара, 'name'-наименование,'imageurl'-url картинки,'amount'-цена,
     * 'quantity' -количество
     */
    public static function get_product_param($product_id)
    {
        $result = array();

        $product = wc_get_product($product_id); // Класс Woo для работы с товаром

        if (method_exists($product, 'get_image_id')) {
            $name = $product->get_post_data()->post_title; //Название товара
            $image_param = wp_get_attachment_image_src($product->get_image_id()); //Урл картинки товара
            $amount = $product->get_price(); //Цена товара
            $quantity = '1'; //Количество товаров - не использую
            //Данные о товаре
            $result = array(
                'article' => $product_id,
                'name' => $name,
                'imageurl' => $image_param[0],
                'amount' => $amount,
                'quantity' => $quantity
            );
        }

        return $result;
    }



    protected static function get_button_name()
    {
        global $product;

        $options = Help::getInstance()->get_options();

        $default_name = __('Buy on click', 'coderun-oneclickwoo');

        if (!isset($options['buyoptions']['namebutton'])) {
            return $default_name;
        }

        $name = null;

        $default_name = $options['buyoptions']['namebutton'];

        if (isset($options['buyoptions']['woo_stock_status_button_text'])) {
            $name = $options['buyoptions']['woo_stock_status_button_text'];
        }

        if (!is_object($product) || empty($product->get_id()) || empty($options['buyoptions']['woo_stock_status_enable'])) {
            return $default_name;
        }

        $stock_status = get_post_meta($product->get_id(), '_stock_status', true);
        //outofstock - нет в наличие
        //instock - в наличие
        //onbackorder - в не выполненом заказе
        if ($stock_status === 'outofstock') {
            return $name;
        }
        return $default_name;
    }
}
