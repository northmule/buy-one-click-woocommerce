<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\ValueObject;

use Coderun\BuyOneClick\Exceptions\ObjectException;
use Coderun\BuyOneClick\Exceptions\VariablesException;
use Coderun\BuyOneClick\Hydrator\CommonHydrator;
use Coderun\BuyOneClick\ValueObject\OrderBlank as OrderFormValueObject;

use function strval;

/**
 * Class OrderDataForAdmin
 *
 * @package Coderun\BuyOneClick\ValueObject
 */
class OrderDataForAdmin
{
    /**
     * Покупатель
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
     * Количество
     *
     * @var string
     */
    protected string $quantityProduct = '';
    /**
     * Ссылка на товар
     *
     * @var string
     */
    protected string $productLinkAdmin;
    /**
     * Данные по вариативному товару
     *
     * @var string
     */
    protected string $variationData = '';
    /**
     * Вся прочая информация(комментарий, вариации и т.д)
     *
     * @var string
     */
    protected string $userComment;
    /**
     * Вариативный?
     *
     * @var boolean
     */
    protected bool $productIsVariable = false;
    /**
     * Название товара
     *
     * @var string
     */
    protected string $productName = '';
    /**
     * Ссылки на файлы
     *
     * @var array
     */
    protected array $files = [];
    /**
     * Внутренний UUID заказа
     *
     * @var string
     */
    protected string $uuid = '';

    /**
     * @param array|null $data
     *
     * @throws \Exception
     */
    public function __construct(?array $data = null)
    {
        if ($data == null) {
            return;
        }
        try {
            $order = (new CommonHydrator())->hydrateArrayToObject(
                $data,
                new OrderFormValueObject()
            );
        } catch (VariablesException | ObjectException $e) {
            $order = null;
        }
        if (!$order instanceof OrderFormValueObject) {
            $this->parseOldData($data);
            return;
        }

        $this->parseData($order);
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
    public function getQuantityProduct(): string
    {
        return $this->quantityProduct;
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
    public function getUserComment(): string
    {
        return $this->userComment;
    }

    /**
     * @return string
     */
    public function getVariationData(): string
    {
        return $this->variationData;
    }

    /**
     * @return bool
     */
    public function isProductIsVariable(): bool
    {
        return $this->productIsVariable;
    }

    /**
     * @return array<int, string>
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getProductName(): string
    {
        return $this->productName;
    }

    /**
     * Старые данные
     *
     * @param array<string, string> $form
     *
     * @return void
     */
    private function parseOldData(array $form): void
    {
        $this->userName = $form['user_name'] ?? '';
        $this->userPhone = $form['user_phone'] ?? '';
        $this->userEmail = $form['user_email'] ?? '';
        $this->quantityProduct = $form['quantity_product'] ?? '';
        $this->userComment = $form['user_cooment'] ?? '';
        $this->productLinkAdmin = $form['product_link_admin'] ?? '';
        $this->productName = $form['product_name'] ?? '';
    }

    /**
     * Данные из объекта
     *
     * @param OrderFormValueObject $data
     *
     * @return void
     */
    private function parseData(OrderFormValueObject $data): void
    {
        $this->userName = $data->getUserName();
        $this->userPhone = $data->getUserPhone();
        $this->userEmail = $data->getUserEmail();
        $this->quantityProduct = strval($data->getQuantityProduct());
        $this->userComment = $data->getUserComment();
        $this->productLinkAdmin = $data->getProductLinkAdmin();
        $this->variationData = $data->getVariationData();
        $this->productIsVariable = $data->isProductIsVariable();
        $this->files = $data->getFilesUrlCollection() ?? [];
        $this->uuid = $data->getOrderUuid() ?? '';
        $this->productName = $data->getProductName();
    }
}
