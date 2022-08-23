<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\SimpleDataObjects;

/**
 * Class ShortcodeParameters
 *
 * @package Coderun\BuyOneClick\SimpleDataObjects
 */
class ShortcodeParameters extends DataTransferObject
{
    /** @var string  */
    public string $id;
    /** @var string  */
    public string $name;
    /** @var string  */
    public string $count;
    /** @var string  */
    public string $price;
    /** @var string  */
    public string $priceWithCurrency;
}
