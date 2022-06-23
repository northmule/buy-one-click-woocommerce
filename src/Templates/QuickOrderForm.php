<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Templates;

use Coderun\BuyOneClick\Help;
use Coderun\BuyOneClick\Options\General as GeneralOptions;

/**
 * Class QuickOrderForm
 *
 * @package Coderun\BuyOneClick\Templates
 */
class QuickOrderForm implements TemplateInterface
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
     * Форма для быстрого заказа
     */
    public function render($params): string
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
}