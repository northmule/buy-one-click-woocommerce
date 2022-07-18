<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Templates;

use Coderun\BuyOneClick\Options\General as GeneralOptions;
use Coderun\BuyOneClick\SimpleDataObjects\DataTransferObject;
use Coderun\BuyOneClick\SimpleDataObjects\FieldsOfOrderForm as FieldsOfOrderFormDataObject;

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
     * @param FieldsOfOrderFormDataObject $fields
     *
     * @return string
     */
    public function render(DataTransferObject $fields): string
    {
        $render = $this;
        ob_start();
        include sprintf('%s/forms/order_form.php', CODERUN_ONECLICKWOO_TEMPLATES_PLUGIN_DIR);
        $form = ob_get_contents();
        ob_end_clean();

        return apply_filters('coderun_oneclickwoo_order_form_html', $form);
    }

    /**
     * @return GeneralOptions
     */
    public function getCommonOptions(): GeneralOptions
    {
        return $this->commonOptions;
    }
}
