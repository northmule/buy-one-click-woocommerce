<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Templates;

use Coderun\BuyOneClick\SimpleDataObjects\DataTransferObject;
use Coderun\BuyOneClick\SimpleDataObjects\OrderButton as OrderButtonDataObject;
use Coderun\BuyOneClick\SimpleDataObjects\CustomOrderButton as CustomOrderButtonDataObject;

/**
 * Class OrderButton
 *
 * @package Coderun\BuyOneClick\Templates
 */
class OrderButton implements TemplateInterface
{
    /**
     * @param OrderButtonDataObject|CustomOrderButtonDataObject $fields
     *
     * @return string
     */
    public function render(DataTransferObject $fields): string
    {
        $render = $this;
        ob_start();
        if ($fields instanceof OrderButtonDataObject) {
            include sprintf('%s/forms/orderButton.php', CODERUN_ONECLICKWOO_TEMPLATES_PLUGIN_DIR);
        } elseif ($fields instanceof CustomOrderButtonDataObject) {
            include sprintf('%s/forms/customOrderButton.php', CODERUN_ONECLICKWOO_TEMPLATES_PLUGIN_DIR);
        }
        $form = ob_get_contents();
        ob_end_clean();

        return $form;
    }
}
