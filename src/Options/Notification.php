<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Options;

use Coderun\BuyOneClick\Constant\Options\Type as OptionsType;

use function boolval;

/**
 * Настройки уведомлений, вкладка настроек
 *
 * Class Notification
 *
 * @package Coderun\BuyOneClick\Options
 */
class Notification extends Base
{
    /**
     * @var string
     */
    protected const ROOT_KEY = OptionsType::NOTIFICATIONS;

    /**
     * Название организации
     *
     * @wpOptionsName namemag
     *
     * @var string
     */
    protected string $organizationName;
    /**
     * Email в поле от кого
     *
     * @wpOptionsName emailfrom
     *
     * @var string
     */
    protected string $emailFromWhom;
    /**
     * Email скрытая копия
     *
     * @wpOptionsName emailbbc
     *
     * @var string
     */
    protected string $emailBcc;
    /**
     * Информация о заказе
     *
     * @wpOptionsName infozakaz_chek
     *
     * @var boolean
     */
    protected bool $enableOrderInformation;
    /**
     * Дополнительное поле из настроек
     *
     * @wpOptionsName dopiczakaz_chek
     *
     * @var boolean
     */
    protected bool $enableAdditionalField;
    /**
     * Ссылки на файлы
     *
     * @wpOptionsName links_to_files
     *
     * @var boolean
     */
    protected bool $enableFileLinks;
    /**
     * Сообщение из доп. поля настроек
     *
     * @wpOptionsName dopiczakaz
     *
     * @var string
     */
    protected string $additionalFieldMessage;
    /**
     * Включить уведомление смс клиенту
     *
     * @wpOptionsName sms_enable_smsc
     *
     * @var boolean
     */
    protected bool $enableSendingSmsToClient;
    /**
     * Включить уведомление смс продавцу
     *
     * @wpOptionsName sms_enable_smsc_saller
     *
     * @var boolean
     */
    protected bool $enableSendingSmsToSeller;
    /**
     * Номер телефона продавца
     *
     * @wpOptionsName sms_phone_saller
     *
     * @var string
     */
    protected string $sellerPhoneNumber;
    /**
     * Логин сервис смс
     *
     * @wpOptionsName sms_login
     *
     * @var string
     */
    protected string $smsServiceLogin;
    /**
     * Пароль сервис смс
     *
     * @wpOptionsName sms_password
     *
     * @var string
     */
    protected string $smsServicePassword;
    /**
     * Sms POST
     *
     * @wpOptionsName sms_methodpost
     *
     * @var boolean
     */
    protected bool $enableSmsServicePostProtocol = true;
    /**
     * Sms https
     *
     * @wpOptionsName sms_https
     *
     * @var boolean
     */
    protected bool $enableSmsServiceHttpsProtocol = true;
    /**
     * Sms кодировка символов
     *
     * @wpOptionsName sms_charset
     *
     * @var string
     */
    protected string $smsCharacterEncoding;
    /**
     * Sms шаблон клиента
     *
     * @wpOptionsName sms_smshablon
     *
     * @var string
     */
    protected string $smsClientTemplate;
    /**
     * Sms шаблон продавца
     *
     * @wpOptionsName sms_smshablon_saller
     *
     * @var string
     */
    protected string $smsSellerTemplate;
    /**
     * sms debug
     *
     * @wpOptionsName sms_debug
     *
     * @var boolean
     */
    protected bool $enableSmsDebug = false;
    /**
     * Цена с учётом налога
     *
     * @wpOptionsName price_including_tax
     *
     * @var boolean
     */
    protected bool $enablePriceWithTax;
    /**
     * Включить информацию о заказе в шаблон WooCommerce
     *
     * @wpOptionsName modificationOrderTemplate
     *
     * @var boolean
     */
    protected bool $enableOrderInformationToTemplateWoo;

    /**
     * Настройки из WordPress в св-ва
     *
     * @param array<string, mixed> $options
     */
    public function __construct(array $options)
    {
        $this->organizationName = $options['namemag'] ?? '';
        $this->emailFromWhom = $options['emailfrom'] ?? '';
        $this->emailBcc = $options['emailbbc'] ?? '';
        $this->enableOrderInformation = boolval($options['infozakaz_chek'] ?? false);
        $this->enableAdditionalField = boolval($options['dopiczakaz_chek'] ?? false);
        $this->enableFileLinks = boolval($options['links_to_files'] ?? false);
        $this->additionalFieldMessage = $options['dopiczakaz'] ?? '';
        $this->enableSendingSmsToClient = boolval($options['sms_enable_smsc'] ?? false);
        $this->enableSendingSmsToSeller = boolval($options['sms_enable_smsc_saller'] ?? false);
        $this->sellerPhoneNumber = $options['sms_phone_saller'] ?? '';
        $this->smsServiceLogin = $options['sms_login'] ?? '';
        $this->smsServicePassword = $options['sms_password'] ?? '';
        $this->enableSmsServicePostProtocol = boolval($options['sms_methodpost'] ?? false);
        $this->enableSmsServiceHttpsProtocol = boolval($options['sms_https'] ?? false);
        $this->smsCharacterEncoding = $options['sms_charset'] ?? '';
        $this->smsClientTemplate = $options['sms_smshablon'] ?? '';
        $this->smsSellerTemplate = $options['sms_smshablon_saller'] ?? '';
        $this->enableSmsDebug = boolval($options['sms_debug'] ?? false);
        $this->enablePriceWithTax = boolval($options['price_including_tax'] ?? false);
        $this->enableOrderInformationToTemplateWoo = boolval($options['modificationOrderTemplate'] ?? false);
    }


    /**
     * @return string
     */
    public function getOrganizationName(): string
    {
        return $this->organizationName;
    }

    /**
     * @param string $organizationName
     *
     * @return Notification
     */
    public function setOrganizationName(string $organizationName): Notification
    {
        $this->organizationName = $organizationName;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmailFromWhom(): string
    {
        return $this->emailFromWhom;
    }

    /**
     * @param string $emailFromWhom
     *
     * @return Notification
     */
    public function setEmailFromWhom(string $emailFromWhom): Notification
    {
        $this->emailFromWhom = $emailFromWhom;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmailBcc(): string
    {
        return $this->emailBcc;
    }

    /**
     * @param string $emailBcc
     *
     * @return Notification
     */
    public function setEmailBcc(string $emailBcc): Notification
    {
        $this->emailBcc = $emailBcc;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnableOrderInformation(): bool
    {
        return $this->enableOrderInformation;
    }

    /**
     * @param bool $enableOrderInformation
     *
     * @return Notification
     */
    public function setEnableOrderInformation(
        bool $enableOrderInformation
    ): Notification {
        $this->enableOrderInformation = $enableOrderInformation;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnableAdditionalField(): bool
    {
        return $this->enableAdditionalField;
    }

    /**
     * @param bool $enableAdditionalField
     *
     * @return Notification
     */
    public function setEnableAdditionalField(
        bool $enableAdditionalField
    ): Notification {
        $this->enableAdditionalField = $enableAdditionalField;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnableFileLinks(): bool
    {
        return $this->enableFileLinks;
    }

    /**
     * @param bool $enableFileLinks
     *
     * @return Notification
     */
    public function setEnableFileLinks(bool $enableFileLinks): Notification
    {
        $this->enableFileLinks = $enableFileLinks;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdditionalFieldMessage(): string
    {
        return $this->additionalFieldMessage;
    }

    /**
     * @param string $additionalFieldMessage
     *
     * @return Notification
     */
    public function setAdditionalFieldMessage(
        string $additionalFieldMessage
    ): Notification {
        $this->additionalFieldMessage = $additionalFieldMessage;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnableSendingSmsToClient(): bool
    {
        return $this->enableSendingSmsToClient;
    }

    /**
     * @param bool $enableSendingSmsToClient
     *
     * @return Notification
     */
    public function setEnableSendingSmsToClient(
        bool $enableSendingSmsToClient
    ): Notification {
        $this->enableSendingSmsToClient = $enableSendingSmsToClient;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnableSendingSmsToSeller(): bool
    {
        return $this->enableSendingSmsToSeller;
    }

    /**
     * @param bool $enableSendingSmsToSeller
     *
     * @return Notification
     */
    public function setEnableSendingSmsToSeller(
        bool $enableSendingSmsToSeller
    ): Notification {
        $this->enableSendingSmsToSeller = $enableSendingSmsToSeller;
        return $this;
    }

    /**
     * @return string
     */
    public function getSellerPhoneNumber(): string
    {
        return $this->sellerPhoneNumber;
    }

    /**
     * @param string $sellerPhoneNumber
     *
     * @return Notification
     */
    public function setSellerPhoneNumber(
        string $sellerPhoneNumber
    ): Notification {
        $this->sellerPhoneNumber = $sellerPhoneNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getSmsServiceLogin(): string
    {
        return $this->smsServiceLogin;
    }

    /**
     * @param string $smsServiceLogin
     *
     * @return Notification
     */
    public function setSmsServiceLogin(string $smsServiceLogin): Notification
    {
        $this->smsServiceLogin = $smsServiceLogin;
        return $this;
    }

    /**
     * @return string
     */
    public function getSmsServicePassword(): string
    {
        return $this->smsServicePassword;
    }

    /**
     * @param string $smsServicePassword
     *
     * @return Notification
     */
    public function setSmsServicePassword(
        string $smsServicePassword
    ): Notification {
        $this->smsServicePassword = $smsServicePassword;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnableSmsServicePostProtocol(): bool
    {
        return $this->enableSmsServicePostProtocol;
    }

    /**
     * @param bool $enableSmsServicePostProtocol
     *
     * @return Notification
     */
    public function setEnableSmsServicePostProtocol(
        bool $enableSmsServicePostProtocol
    ): Notification {
        $this->enableSmsServicePostProtocol = $enableSmsServicePostProtocol;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnableSmsServiceHttpsProtocol(): bool
    {
        return $this->enableSmsServiceHttpsProtocol;
    }

    /**
     * @param bool $enableSmsServiceHttpsProtocol
     *
     * @return Notification
     */
    public function setEnableSmsServiceHttpsProtocol(
        bool $enableSmsServiceHttpsProtocol
    ): Notification {
        $this->enableSmsServiceHttpsProtocol = $enableSmsServiceHttpsProtocol;
        return $this;
    }

    /**
     * @return string
     */
    public function getSmsCharacterEncoding(): string
    {
        return $this->smsCharacterEncoding;
    }

    /**
     * @param string $smsCharacterEncoding
     *
     * @return Notification
     */
    public function setSmsCharacterEncoding(
        string $smsCharacterEncoding
    ): Notification {
        $this->smsCharacterEncoding = $smsCharacterEncoding;
        return $this;
    }

    /**
     * @return string
     */
    public function getSmsClientTemplate(): string
    {
        return $this->smsClientTemplate;
    }

    /**
     * @param string $smsClientTemplate
     *
     * @return Notification
     */
    public function setSmsClientTemplate(
        string $smsClientTemplate
    ): Notification {
        $this->smsClientTemplate = $smsClientTemplate;
        return $this;
    }

    /**
     * @return string
     */
    public function getSmsSellerTemplate(): string
    {
        return $this->smsSellerTemplate;
    }

    /**
     * @param string $smsSellerTemplate
     *
     * @return Notification
     */
    public function setSmsSellerTemplate(
        string $smsSellerTemplate
    ): Notification {
        $this->smsSellerTemplate = $smsSellerTemplate;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnableSmsDebug(): bool
    {
        return $this->enableSmsDebug;
    }

    /**
     * @param bool $enableSmsDebug
     *
     * @return Notification
     */
    public function setEnableSmsDebug(bool $enableSmsDebug): Notification
    {
        $this->enableSmsDebug = $enableSmsDebug;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnablePriceWithTax(): bool
    {
        return $this->enablePriceWithTax;
    }

    /**
     * @param bool $enablePriceWithTax
     *
     * @return Notification
     */
    public function setEnablePriceWithTax(
        bool $enablePriceWithTax
    ): Notification {
        $this->enablePriceWithTax = $enablePriceWithTax;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnableOrderInformationToTemplateWoo(): bool
    {
        return $this->enableOrderInformationToTemplateWoo;
    }

    /**
     * @param bool $enableOrderInformationToTemplateWoo
     *
     * @return Notification
     */
    public function setEnableOrderInformationToTemplateWoo(
        bool $enableOrderInformationToTemplateWoo
    ): Notification {
        $this->enableOrderInformationToTemplateWoo
            = $enableOrderInformationToTemplateWoo;
        return $this;
    }

    /**
     * @inheritDoc
     *
     * @return string
     */
    protected function getRootKey(): string
    {
        return self::ROOT_KEY;
    }
}
