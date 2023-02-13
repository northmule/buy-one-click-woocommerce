<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\ValueObject;

use Coderun\BuyOneClick\Exceptions\ObjectException;
use Coderun\BuyOneClick\Options\Notification as NotificationOptions;
use Coderun\BuyOneClick\SimpleDataObjects\DownloadedFile;
use Coderun\BuyOneClick\Utils\Hooks;
use Coderun\BuyOneClick\Utils\Uuid as UuidUtils;
use WC_Data_Exception;

/**
 * Class OrderForm
 *
 * @package Coderun\BuyOneClick\ValueObject
 */
class OrderForm
{
    /**
     * Имя
     *
     * @var string
     */
    protected string $userName = '';
    /**
     * Телефон
     *
     * @var string
     */
    protected string $userPhone = '';
    /**
     * Email
     *
     * @var string
     */
    protected string $userEmail = '';
    /**
     * Комментарий, доп. поле
     *
     * @var string
     */
    protected string $userComment = '';
    protected string $orderComment = '';
    protected int $productId = 0;
    protected bool $productIsVariable = false;
    protected string $productName = '';
    /**
     * Информация о вариации
     *
     * @var string
     */
    protected string $variationData = '';
    protected string $productOriginalName = '';
    protected float $productPrice = 0.00;
    protected float $productPriceWithTax = 0.00;
    protected string $productLinkAdmin = '';
    protected string $productLinkUser = '';
    protected string $companyName = '';
    protected string $orderAdminComment = '';
    protected bool $conset = true;
    /**
     * Поля формы
     *
     * @var array<int,array<mixed>>
     */
    protected array $formsField = [];
    protected string $orderTime = '';
    protected int $custom = 10;
    /**
     * Файлы
     *
     * @var array<int, string>
     */
    protected array $files = [];
    protected int $quantityProduct = 1;
    /**
     * URL на товар
     *
     * @var string
     */
    protected string $productUrl = '';
    /**
     * @var array<int, string>
     */
    protected array $formData = [];
    /**
     * @var array<int,string>
     */
    protected array $filesUrlCollection = [];
    protected string $filesLink = '';
    /**
     * uuid4 автогенерируемый
     *
     * @var string
     */
    protected string $orderUuid = '';
    /**
     * Является товаром WooCommerce
     * Товар может быть с произвольным ИД, не являясь товаром WooCommerce
     *
     * @var boolean
     */
    protected bool $isWooCommerceProduct = false;

    /**
     * @param array<int, string>  $formData
     * @param NotificationOptions $notificationOptions
     * @param bool                $variationEnable
     * @param array<int, string>  $files
     *
     * @throws WC_Data_Exception
     */
    public function __construct(
        array $formData,
        NotificationOptions $notificationOptions,
        array $files = []
    ) {
        $this->formData = $formData;
        $this->userName = $this->formDateParse('txtname');
        $this->userPhone = $this->formDateParse('txtphone');
        $this->userEmail = sanitize_email($this->formDateParse('txtemail'));
        $this->userComment = $this->formDateParse('message');
        $this->orderComment = $this->formDateParse('message');
        $this->productId = (int) $this->formDateParse('idtovar');
        $this->isWooCommerceProduct = boolval(wc_get_product($this->productId));
        $this->productUrl = strval(get_the_permalink($this->productId));
        $this->productName = $this->formDateParse('nametovar');
        $this->productOriginalName = $this->formDateParse('nametovar');
        $this->productPrice = (float) $this->formDateParse('pricetovar');
        $this->productLinkAdmin = $this->collectLinkToProductForAdministrator();
        $this->productLinkUser = $this->collectLinkToProductForUser($this->productUrl);
        $this->companyName = $notificationOptions->getOrganizationName();
        $this->orderAdminComment = $notificationOptions->getAdditionalFieldMessage();
        $this->conset = (bool) $this->formDateParse('conset_personal_data');
        $this->formsField = $this->formDateLegacyParse();
        $this->orderTime = current_time('mysql');
        $this->custom = (int) $this->formDateParse('custom');
        $this->files = $files;
        $this->quantityProduct = $this->formDateParse('quantity_product') == ''
            ? 1 : intval($this->formDateParse('quantity_product'));
        $this->fillInPriceWithTax();
        $this->fillingWithVariations();
        $this->filesUrlCollection = $this->collectUrlToUploadedFiles($files);
        foreach ($this->filesUrlCollection as $fileUrl) {
            $this->filesLink = sprintf('</br> %s', $this->collectLinkToProductForUser($fileUrl));
        }

        $this->orderUuid = UuidUtils::uuidGenerator();
    }

    /**
     * Заполняет цену с учётом налога
     *
     * @return void
     * @throws WC_Data_Exception
     */
    private function fillInPriceWithTax(): void
    {
        if (!$this->isWooCommerceProduct) {
            return;
        }
        $this->productPriceWithTax = wc_get_price_including_tax(wc_get_product($this->productId));
    }

    /**
     * Данные от дополнения вариативных товаров
     *
     * @return void
     */
    private function fillingWithVariations(): void
    {
        $this->variationData = Hooks::filterDataAboutSelectedVariationFromForm($this->getFormsField());
        $variation_id = Hooks::filterGetIdOfSelectedVariation($this->getFormsField());
        if ($variation_id > 0) {
            $this->productIsVariable = true;
            $this->productId = $variation_id;
        }
    }

    /**
     * @return string
     */
    private function collectLinkToProductForAdministrator(): string
    {
        return sprintf(
            '<a href="%s" target="_blank"><span class="glyphicon glyphicon-share"></span></a>',
            $this->productUrl
        );
    }
    /**
     * @return string
     */
    private function collectLinkToProductForUser(string $url): string
    {
        return sprintf(
            '<a href="%s" target="_blank">%s</a>',
            $url,
            __('Look', 'coderun-oneclickwoo')
        );
    }


    /**
     * @param $key
     *
     * @return array|string
     */
    private function formDateParse($key)
    {
        return $this->arrayParse($this->formData, $key);
    }

    /**
     * Проверяют указанное поле и возвращает значение если есть
     *
     * @param  array<string, mixed> $data
     * @param  string               $key  ключ
     *                                    массива
     * @return array|string
     */
    private function arrayParse(array $data, string $key)
    {
        $result = '';
        if (isset($data[$key])) {
            if (!is_array($data[$key])) {
                $result = wp_specialchars_decode(esc_html($data[$key]), ENT_QUOTES);
            } else {
                $result = $data[$key];
            }
        }
        if (is_string($result)) {
            $result = trim($result);
        }
        return $result;
    }

    /**
     * Для совместимости данных формы в виде массива name -> value
     *
     * @return array
     */
    private function formDateLegacyParse(): array
    {
        $result = [];
        $count = 0;
        foreach ($this->formData as $key => $value) {
            $result[$count]['name'] = $key;
            $result[$count]['value'] = $value;
            $count++;
        }
        return $result;
    }


    /**
     * @param array<int, DownloadedFile> $files
     *
     * @return array<string,mixed>
     */
    private function collectUrlToUploadedFiles(array $files): array
    {
        $result = [];
        foreach ($files as $file) {
            $result[] = $file->url;
        }
        return array_filter($result);
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }


    /**
     * @return string
     */
    public function getUserPhone(): string
    {
        return $this->userPhone;
    }


    /**
     * @return string
     */
    public function getUserEmail(): string
    {
        return $this->userEmail;
    }


    /**
     * @return string
     */
    public function getUserComment(): string
    {
        return $this->userComment;
    }


    /**
     * @return string
     */
    public function getOrderComment(): string
    {
        return $this->orderComment;
    }


    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }


    /**
     * @return string
     */
    public function getProductName()
    {
        return $this->productName;
    }


    /**
     * @return string
     */
    public function getVariationData(): string
    {
        return $this->variationData;
    }


    /**
     * @return string
     */
    public function getProductOriginalName(): string
    {
        return $this->productOriginalName;
    }


    /**
     * @return float
     */
    public function getProductPrice(): float
    {
        return $this->productPrice;
    }


    /**
     * @return float
     */
    public function getProductPriceWithTax(): float
    {
        return $this->productPriceWithTax;
    }


    /**
     * @return string
     */
    public function getProductLinkAdmin(): string
    {
        return $this->productLinkAdmin;
    }


    /**
     * @return string
     */
    public function getProductLinkUser(): string
    {
        return $this->productLinkUser;
    }


    /**
     * @return string
     */
    public function getCompanyName(): string
    {
        return $this->companyName;
    }


    /**
     * @return string
     */
    public function getOrderAdminComment(): string
    {
        return $this->orderAdminComment;
    }


    /**
     * @return bool
     */
    public function isConset(): bool
    {
        return $this->conset;
    }

    /**
     * @return array<int, array<string,string>>
     */
    public function getFormsField(): array
    {
        return $this->formsField;
    }


    /**
     * @return string
     */
    public function getOrderTime(): string
    {
        return $this->orderTime;
    }


    /**
     * @return int
     */
    public function getCustom(): int
    {
        return $this->custom;
    }


    /**
     * @return array<int,string>
     */
    public function getFiles(): array
    {
        return $this->files;
    }



    /**
     * @return int
     */
    public function getQuantityProduct(): int
    {
        return $this->quantityProduct;
    }



    /**
     * @return string
     */
    public function getProductUrl(): string
    {
        return $this->productUrl;
    }



    /**
     * @return array|mixed[]
     */
    public function getFormData(): array
    {
        return $this->formData;
    }


    /**
     * @return array|mixed[]
     */
    public function getFilesUrlCollection(): array
    {
        return $this->filesUrlCollection;
    }

    /**
     * @return string
     */
    public function getFilesLink(): string
    {
        return $this->filesLink;
    }



    /**
     * @return bool
     */
    public function isProductIsVariable(): bool
    {
        return $this->productIsVariable;
    }


    /**
     * @return string
     */
    public function getOrderUuid(): string
    {
        return $this->orderUuid;
    }


    /**
     * @return bool
     */
    public function isWooCommerceProduct(): bool
    {
        return $this->isWooCommerceProduct;
    }


    /**
     * Для правильной гидрации (только сеттеры)
     *
     * @param string            $name
     * @param  array<int, mixed> $arguments
     *
     * @return void
     */
    public function __call(string $name, $arguments)
    {
        if (substr($name, 0, 3) !== 'set') {
            throw ObjectException::setterDoesNotExist($name, self::class);
        }
        $property = lcfirst(substr($name, 3));
        $this->{$property} = $arguments[0] ?? '';
    }
}
