<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Options;

/**
 * Class Base
 *
 * @package Coderun\BuyOneClick\Options
 */
abstract class Base implements OptionsInterface
{
    use OptionsTrait;

    /**
     * Имя докБлока для разбора значений опций WoordPress
     *
     * @var string
     */
    protected const DOC_OPTIONS_NAME = 'wpOptionsName';

    /**
     * Имя корневой настройки
     *
     * @return string
     */
    abstract protected function getRootKey(): string;
}
