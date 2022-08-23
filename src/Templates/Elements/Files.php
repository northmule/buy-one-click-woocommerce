<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Templates\Elements;

use Coderun\BuyOneClick\Options\General as GeneralOptions;
use Coderun\BuyOneClick\SimpleDataObjects\DataTransferObject;

/**
 * Class Files
 *
 * @package Coderun\BuyOneClick\Templates\Elements
 */
class Files implements ElementInterface
{
    /**
     * Настройки плагина
     *
     * @var GeneralOptions
     */
    protected GeneralOptions $commonOptions;

    public function __construct(GeneralOptions $commonOptions)
    {
        $this->commonOptions = $commonOptions;
    }


    /**
     * @inheritDoc
     *
     * @return string
     */
    public function render(DataTransferObject $params): string
    {
        if ($this->commonOptions->isEnableFieldWithFiles()) {
            ob_start();
            $render = $this;
            include_once sprintf('%s/forms/file_uploader.php', CODERUN_ONECLICKWOO_TEMPLATES_PLUGIN_DIR);
            $form = ob_get_contents();
            ob_end_clean();
            return apply_filters('coderun_oneclickwoo_order_form_html', $form);
        }

        return '';
    }

    /**
     * @return GeneralOptions
     */
    public function getCommonOptions(): GeneralOptions
    {
        return $this->commonOptions;
    }
}
