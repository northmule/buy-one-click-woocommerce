<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\ValueObject;

use Coderun\BuyOneClick\LoadFile;
use Coderun\BuyOneClick\Options\General as GeneralOptions;
use Coderun\BuyOneClick\Options\Notification as NotificationOptions;
use Coderun\BuyOneClick\Order;
use Coderun\BuyOneClick\VariationsAddition;
use WC_Data_Exception;

/**
 * Class OrderForm
 *
 * @package Coderun\BuyOneClick\ValueObject
 */
class OrderForm
{
    protected string $userName;
    protected string $userPhone;
    protected string $userEmail;
    protected string $userComment;
    protected string $orderComment;
    protected int $productId;
    protected string $productName;
    
    /**
     * Информация о вариации
     *
     * @var string
     */
    protected string $variationData = '';
    protected string $productOriginalName;
    protected float $productPrice;
    protected float $productPriceWithTax;
    protected string $productLinkAdmin;
    protected string $productLinkUser;
    protected string $companyName;
    protected string $orderAdminComment;
    protected bool $conset;
    protected array $formsField;
    protected string $orderTime;
    protected int $custom;
    protected ?array $files;
    protected int $quantityProduct;
    
    /**
     * URL на товар
     *
     * @var string|false|\WP_Error
     */
    protected string $productUrl;
    protected array $formData;
    protected NotificationOptions $notificationOptions;
    protected array $filesUrlCollection;
    protected string $filesLink;
    
    /**
     * @param array<string, mixed> $formData
     *
     * @throws WC_Data_Exception
     */
    public function __construct(
        array $formData,
        NotificationOptions $notificationOptions,
        bool $variationEnable = false
    )
    {
        $this->formData = $formData;
        $this->notificationOptions = $notificationOptions;
        $this->userName = $this->formDateParse('txtname');
        $this->userPhone = $this->formDateParse('txtphone');
        $this->userEmail = sanitize_email($this->formDateParse('txtemail'));
        $this->userComment = $this->formDateParse('message');
        $this->orderComment = $this->formDateParse('message');
        $this->productId = (int)$this->formDateParse('idtovar');
        $this->productUrl = get_the_permalink($this->productId);
        $this->productName = $this->formDateParse('nametovar');
        $this->productOriginalName = $this->formDateParse('nametovar');
        $this->productPrice = (float)$this->formDateParse('pricetovar');
        $this->productLinkAdmin = $this->collectLinkToProductForAdministrator();
        $this->productLinkUser = $this->collectLinkToProductForUser($this->productUrl);
        $this->companyName = $this->notificationOptions->getOrganizationName();
        $this->orderAdminComment = $this->notificationOptions->getAdditionalFieldMessage();
        $this->conset = (bool)$this->formDateParse('conset_personal_data');
        $this->formsField = $this->formDateLegacyParse();
        $this->orderTime = current_time('mysql');
        $this->custom = (int)$this->formDateParse('custom');
        $this->files = array_map('array_filter',$_FILES['files'] ?? []);
        $this->quantityProduct = (int)$this->formDateParse('quantity_product');
        $this->fillInPriceWithTax();
        if ($variationEnable) {
            $this->fillingWithVariations();
        }
        $dataAboutUploadedFiles = LoadFile::getInstance()->load();
        $this->filesUrlCollection = $this->collectUrlToUploadedFiles($dataAboutUploadedFiles);
        foreach ($this->filesUrlCollection as $fileUrl) {
            $this->filesLink = sprintf('</br> %s', $this->collectLinkToProductForUser($fileUrl));
        }
    }
    
    /**
     * Заполняет цену с учётом налога
     *
     * @return void
     * @throws WC_Data_Exception
     */
    private function fillInPriceWithTax(): void
    {
        $wcOrder = Order::getInstance()->create_order(['product_id' => $this->productId]);
        $this->productPriceWithTax = (float)Order::getInstance()->calculate_order_totals($wcOrder);
        $wcOrder->delete(true); // todo переделать
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
     * @param array<string, mixed> $data
     * @param string $key ключ массива
     * @return array|string
     */
    private function arrayParse(array $data, $key)
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
     * @param array $files
     *
     * @return array<string,mixed>
     */
    private function collectUrlToUploadedFiles(array $files): array
    {
        $result = [];
        foreach ($files as $file) {
            $result[] = $file['url'] ?? null;
        }
        return array_filter($result);
    }
    
    /**
     * Файлы в виде ссылок и строки
     * @param array $files
     * @return string
     */
    private function collectLinksToUploadedFiles(array $files):string
    {
        $result = '';
        $count = 1;
        foreach ($this->collectUrlToUploadedFiles($files) as $url) {
            $url = trim($url);
            if (strlen($url) == 0) {
                continue;
            }
            $result .=sprintf(
                '<br><a href="%s">%s %s</a>',
                $url,
                __('File', 'coderun-oneclickwoo'),
                $count++
            );
        }
        
        return $result;
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
    public function setProductPriceWithTax(float $productPriceWithTax
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

    
}