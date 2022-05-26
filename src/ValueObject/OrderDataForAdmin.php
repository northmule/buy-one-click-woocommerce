<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\ValueObject;

use Coderun\BuyOneClick\Exceptions\ObjectException;
use Coderun\BuyOneClick\Exceptions\VariablesException;
use Coderun\BuyOneClick\Hydrator\CommonHydrator;
use Coderun\BuyOneClick\ValueObject\OrderForm as OrderFormValueObject;

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
     * @var string
     */
    protected string $userName = '';
    
    /**
     * Телефон
     * @var string
     */
    protected string $userPhone = '';
    
    /**
     * Email
     * @var string
     */
    protected string $userEmail = '';
    
    /**
     * Количество
     * @var string
     */
    protected string $quantityProduct = '';
    
    /**
     * Ссылка на товар
     * @var string
     */
    protected string $productLinkAdmin;
    
    /**
     * Вся прочая информация(комментарий, вариации и т.д)
     * @var string
     */
    protected string $userComment;
    
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
                new OrderFormValueObject(null,null,null)
            );
        } catch (VariablesException|ObjectException $e) {
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
     * Старые данные
     *
     * @param array<string, string> $data
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
    }
    
    
}