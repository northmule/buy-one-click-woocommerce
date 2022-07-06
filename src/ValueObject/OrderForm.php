<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\ValueObject;

use Coderun\BuyOneClick\Options\Notification as NotificationOptions;
use Coderun\BuyOneClick\Repository\Order;
use Coderun\BuyOneClick\SimpleDataObjects\DownloadedFile;
use Coderun\BuyOneClick\Utils\Uuid as UuidUtils;
use Coderun\BuyOneClick\VariationsAddition;
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
    protected array $formsField  = [];
    protected string $orderTime  = '';
    protected int $custom = 10;
    protected array $files = [];
    protected int $quantityProduct = 1;

    /**
     * URL на товар
     *
     * @var string|false|\WP_Error
     */
    protected string $productUrl = '';
    protected array $formData = [];
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
     * @var bool
     */
    protected bool $isWooCommerceProduct = false;

    /**
     * @param array<string, mixed> $formData
     *
     * @throws WC_Data_Exception
     */
    public function __construct(
        array $formData,
        NotificationOptions $notificationOptions,
        bool $variationEnable = false,
        array $files = []
    ) {
        $this->formData = $formData;
        $this->userName = $this->formDateParse('txtname');
        $this->userPhone = $this->formDateParse('txtphone');
        $this->userEmail = sanitize_email($this->formDateParse('txtemail'));
        $this->userComment = $this->formDateParse('message');
        $this->orderComment = $this->formDateParse('message');
        $this->productId = (int)$this->formDateParse('idtovar');
        $this->isWooCommerceProduct = boolval(wc_get_product($this->productId));
        $this->productUrl = strval(get_the_permalink($this->productId));
        $this->productName = $this->formDateParse('nametovar');
        $this->productOriginalName = $this->formDateParse('nametovar');
        $this->productPrice = (float)$this->formDateParse('pricetovar');
        $this->productLinkAdmin = $this->collectLinkToProductForAdministrator();
        $this->productLinkUser = $this->collectLinkToProductForUser($this->productUrl);
        $this->companyName = $notificationOptions->getOrganizationName();
        $this->orderAdminComment = $notificationOptions->getAdditionalFieldMessage();
        $this->conset = (bool)$this->formDateParse('conset_personal_data');
        $this->formsField = $this->formDateLegacyParse();
        $this->orderTime = current_time('mysql');
        $this->custom = (int)$this->formDateParse('custom');
        $this->files = array_map('array_filter', $_FILES['files'] ?? []);
        $this->quantityProduct = $this->formDateParse('quantity_product') == ''
            ? 1 : intval($this->formDateParse('quantity_product'));
        $this->fillInPriceWithTax();
        if ($variationEnable) {
            $this->fillingWithVariations();
        }
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
        $wcOrder = Order::getInstance()->createWooCommerceOrderWithoutSaving($this->productId);
        $this->productPriceWithTax = (float)Order::getInstance()->calculate_order_totals($wcOrder);
        $wcOrder->delete();
        unset($wcOrder);
    }

    /**
     * Данные от дополнения вариативных товаров
     *
     * @return void
     */
    private function fillingWithVariations(): void
    {
        $pluginVariations = VariationsAddition::getInstance();
        $this->variationData = $pluginVariations->getVariableProductInfo($this->getFormsField());
        if (($variation_id = $pluginVariations->getVariationId($this->getFormsField())) > 0) {
            $this->productIsVariable = true;
            $this->productId = (int)$variation_id;
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
     * @param array<string, mixed> $data
     * @param string $key ключ массива
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
     * @return array|string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param array|string $userName
     *
     * @return OrderForm
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
        return $this;
    }

    /**
     * @return array|string
     */
    public function getUserPhone()
    {
        return $this->userPhone;
    }

    /**
     * @param array|string $userPhone
     *
     * @return OrderForm
     */
    public function setUserPhone($userPhone)
    {
        $this->userPhone = $userPhone;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    /**
     * @param string $userEmail
     *
     * @return OrderForm
     */
    public function setUserEmail(string $userEmail): OrderForm
    {
        $this->userEmail = $userEmail;
        return $this;
    }

    /**
     * @return array|string
     */
    public function getUserComment()
    {
        return $this->userComment;
    }

    /**
     * @param array|string $userComment
     *
     * @return OrderForm
     */
    public function setUserComment($userComment)
    {
        $this->userComment = $userComment;
        return $this;
    }

    /**
     * @return array|string
     */
    public function getOrderComment()
    {
        return $this->orderComment;
    }

    /**
     * @param array|string $orderComment
     *
     * @return OrderForm
     */
    public function setOrderComment($orderComment)
    {
        $this->orderComment = $orderComment;
        return $this;
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     *
     * @return OrderForm
     */
    public function setProductId(int $productId): OrderForm
    {
        $this->productId = $productId;
        return $this;
    }

    /**
     * @return array|string
     */
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * @param array|string $productName
     *
     * @return OrderForm
     */
    public function setProductName($productName)
    {
        $this->productName = $productName;
        return $this;
    }

    /**
     * @return string
     */
    public function getVariationData(): string
    {
        return $this->variationData;
    }

    /**
     * @param string $variationData
     *
     * @return OrderForm
     */
    public function setVariationData(string $variationData): OrderForm
    {
        $this->variationData = $variationData;
        return $this;
    }

    /**
     * @return array|string
     */
    public function getProductOriginalName()
    {
        return $this->productOriginalName;
    }

    /**
     * @param array|string $productOriginalName
     *
     * @return OrderForm
     */
    public function setProductOriginalName($productOriginalName)
    {
        $this->productOriginalName = $productOriginalName;
        return $this;
    }

    /**
     * @return float
     */
    public function getProductPrice(): float
    {
        return $this->productPrice;
    }

    /**
     * @param float $productPrice
     *
     * @return OrderForm
     */
    public function setProductPrice(float $productPrice): OrderForm
    {
        $this->productPrice = $productPrice;
        return $this;
    }

    /**
     * @return float
     */
    public function getProductPriceWithTax(): float
    {
        return $this->productPriceWithTax;
    }

    /**
     * @param float $productPriceWithTax
     *
     * @return OrderForm
     */
    public function setProductPriceWithTax(
        float $productPriceWithTax
    ): OrderForm {
        $this->productPriceWithTax = $productPriceWithTax;
        return $this;
    }

    /**
     * @return string
     */
    public function getProductLinkAdmin(): string
    {
        return $this->productLinkAdmin;
    }

    /**
     * @param string $productLinkAdmin
     *
     * @return OrderForm
     */
    public function setProductLinkAdmin(string $productLinkAdmin): OrderForm
    {
        $this->productLinkAdmin = $productLinkAdmin;
        return $this;
    }

    /**
     * @return string
     */
    public function getProductLinkUser(): string
    {
        return $this->productLinkUser;
    }

    /**
     * @param string $productLinkUser
     *
     * @return OrderForm
     */
    public function setProductLinkUser(string $productLinkUser): OrderForm
    {
        $this->productLinkUser = $productLinkUser;
        return $this;
    }

    /**
     * @return array|string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @param array|string $companyName
     *
     * @return OrderForm
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
        return $this;
    }

    /**
     * @return array|string
     */
    public function getOrderAdminComment()
    {
        return $this->orderAdminComment;
    }

    /**
     * @param array|string $orderAdminComment
     *
     * @return OrderForm
     */
    public function setOrderAdminComment($orderAdminComment)
    {
        $this->orderAdminComment = $orderAdminComment;
        return $this;
    }

    /**
     * @return bool
     */
    public function isConset(): bool
    {
        return $this->conset;
    }

    /**
     * @param bool $conset
     *
     * @return OrderForm
     */
    public function setConset(bool $conset): OrderForm
    {
        $this->conset = $conset;
        return $this;
    }

    /**
     * @return array
     */
    public function getFormsField(): array
    {
        return $this->formsField;
    }

    /**
     * @param array $formsField
     *
     * @return OrderForm
     */
    public function setFormsField(array $formsField): OrderForm
    {
        $this->formsField = $formsField;
        return $this;
    }

    /**
     * @return int|string
     */
    public function getOrderTime()
    {
        return $this->orderTime;
    }

    /**
     * @param int|string $orderTime
     *
     * @return OrderForm
     */
    public function setOrderTime($orderTime)
    {
        $this->orderTime = $orderTime;
        return $this;
    }

    /**
     * @return int
     */
    public function getCustom(): int
    {
        return $this->custom;
    }

    /**
     * @param int $custom
     *
     * @return OrderForm
     */
    public function setCustom(int $custom): OrderForm
    {
        $this->custom = $custom;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getFiles(): ?array
    {
        return $this->files;
    }

    /**
     * @param array|null $files
     *
     * @return OrderForm
     */
    public function setFiles(?array $files): OrderForm
    {
        $this->files = $files;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantityProduct(): int
    {
        return $this->quantityProduct;
    }

    /**
     * @param int $quantityProduct
     *
     * @return OrderForm
     */
    public function setQuantityProduct(int $quantityProduct): OrderForm
    {
        $this->quantityProduct = $quantityProduct;
        return $this;
    }

    /**
     * @return false|string|\WP_Error
     */
    public function getProductUrl()
    {
        return $this->productUrl;
    }

    /**
     * @param false|string|\WP_Error $productUrl
     *
     * @return OrderForm
     */
    public function setProductUrl($productUrl)
    {
        $this->productUrl = $productUrl;
        return $this;
    }

    /**
     * @return array|mixed[]
     */
    public function getFormData(): array
    {
        return $this->formData;
    }

    /**
     * @param array|mixed[] $formData
     *
     * @return OrderForm
     */
    public function setFormData(array $formData): OrderForm
    {
        $this->formData = $formData;
        return $this;
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
     * @param array|mixed[] $filesUrlCollection
     *
     * @return OrderForm
     */
    public function setFilesUrlCollection(array $filesUrlCollection): OrderForm
    {
        $this->filesUrlCollection = $filesUrlCollection;
        return $this;
    }

    /**
     * @param string $filesLink
     *
     * @return OrderForm
     */
    public function setFilesLink(string $filesLink): OrderForm
    {
        $this->filesLink = $filesLink;
        return $this;
    }

    /**
     * @return bool
     */
    public function isProductIsVariable(): bool
    {
        return $this->productIsVariable;
    }

    /**
     * @param bool $productIsVariable
     *
     * @return OrderForm
     */
    public function setProductIsVariable(bool $productIsVariable): OrderForm
    {
        $this->productIsVariable = $productIsVariable;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderUuid(): string
    {
        return $this->orderUuid;
    }

    /**
     * @param string $orderUuid
     *
     * @return OrderForm
     */
    public function setOrderUuid(string $orderUuid): OrderForm
    {
        $this->orderUuid = $orderUuid;
        return $this;
    }
    
    /**
     * @return bool
     */
    public function isWooCommerceProduct(): bool
    {
        return $this->isWooCommerceProduct;
    }
    
    /**
     * @param bool $isWooCommerceProduct
     *
     * @return OrderForm
     */
    public function setIsWooCommerceProduct(bool $isWooCommerceProduct
    ): OrderForm {
        $this->isWooCommerceProduct = $isWooCommerceProduct;
        return $this;
    }
    
    
}
