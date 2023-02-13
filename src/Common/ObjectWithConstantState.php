<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Common;

use Coderun\BuyOneClick\Utils\Hooks;


/**
 * Что то вроде глобальной переменной живущей от начала до конца
 *
 * Class ObjectWithConstantState
 *
 * @package Coderun\BuyOneClick\Common
 */
class ObjectWithConstantState
{
    /**
     * @var ObjectWithConstantState|null
     */
    protected static $_instance;
    /**
     * Наличие плагина вариаций в системе
     *
     * @var boolean
     */
    protected bool $variations = false;
    
    protected function __construct()
    {
        $this->variations = Hooks::filterVariationsPluginIsUsed($this);
    }

    /**
     * @return ObjectWithConstantState
     */
    public static function getInstance(): ObjectWithConstantState
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * @return bool
     */
    public function isVariations(): bool
    {
        return $this->variations;
    }
}
