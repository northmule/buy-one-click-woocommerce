<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Options;

use Coderun\BuyOneClick\Constant\Options\ButtonPosition;
use Coderun\BuyOneClick\Constant\Options\Type as OptionsType;
use Coderun\BuyOneClick\Constant\OrderStatus;

use function boolval;
use function intval;

/**
 * Class General
 */
class General extends Base
{
    /**
     * @var string
     */
    protected const ROOT_KEY = OptionsType::GENERAL;

    /**
     * Режим работы плагина
     *
     * @wpOptionsName plugin_work_mode
     *
     * @var integer
     */
    protected int $pluginWorkMode = 0;
    /**
     * Включить/отключить кнопку
     *
     * @wpOptionsName enable_button
     *
     * @var boolean
     */
    protected bool $enableButton = false;
    /**
     * Включить/отключить кнопку шорткод
     *
     * @wpOptionsName enable_button_shortcod
     *
     * @var boolean
     */
    protected bool $enableButtonShortcode;
    /**
     * Имя кнопки
     *
     * @wpOptionsName namebutton
     *
     * @var string|null
     */
    protected ?string $nameButton;
    /**
     * Позиция кнопки
     *
     * @wpOptionsName positionbutton
     *
     * @var string
     */
    protected string $positionButton = ButtonPosition::WOOCOMMERCE_AFTER_ADD_TO_CART_BUTTON;
    /**
     * Позиция кнопки для товара которого нет в наличие
     *
     * @wpOptionsName positionbutton_out_stock
     *
     * @var string
     */
    protected string $positionButtonOutStock = '';
    /**
     * Записывать заказы в таблицу WooCommerce
     *
     * @wpOptionsName add_tableorder_woo
     *
     * @var boolean
     */
    protected bool $addAnOrderToWooCommerce = false;
    /**
     * Включить кнопку в категории
     *
     * @wpOptionsName enable_button_category
     *
     * @var boolean
     */
    protected bool $enableButtonCategory;
    /**
     * Позиция кнопки в категории
     *
     * @wpOptionsName positionbutton_category
     *
     * @var string
     */
    protected string $buttonPositionInCategory = ButtonPosition::WOOCOMMERCE_AFTER_SHOP_LOOP_ITEM;
    /**
     * Включить информацию о товаре на форму
     *
     * @wpOptionsName infotovar_chek
     *
     * @var boolean
     */
    protected bool $enableProductInformation;
    /**
     * Включить поле с именем на форму
     *
     * @wpOptionsName fio_chek
     *
     * @var boolean
     */
    protected bool $enableFieldWithName;
    /**
     * Включить поле с телефоном на форму
     *
     * @wpOptionsName fon_chek
     *
     * @var boolean
     */
    protected bool $enableFieldWithPhone;
    /**
     * Включить поле с email на форму
     *
     * @wpOptionsName email_chek
     *
     * @var boolean
     */
    protected bool $enableFieldWithEmail;
    /**
     * Включить поле с комментарием на форму
     *
     * @wpOptionsName dopik_chek
     *
     * @var boolean
     */
    protected bool $enableFieldWithComment;
    /**
     * Включить поле с количеством на форму
     *
     * @wpOptionsName add_quantity_form
     *
     * @var boolean
     */
    protected bool $enableFieldWithQuantity;
    /**
     * Включить поле с файлами на форму
     *
     * @wpOptionsName upload_input_file_chek
     *
     * @var boolean
     */
    protected bool $enableFieldWithFiles;
    /**
     * Описание для поля Имя
     *
     * @wpOptionsName fio_descript
     *
     * @var string
     */
    protected string $descriptionForFieldName;
    /**
     * Описание для поля Телефон
     *
     * @wpOptionsName fon_descript
     *
     * @var string
     */
    protected string $descriptionForFieldPhone;
    /**
     * Описание для поля Формат телефон
     *
     * @wpOptionsName fon_format
     *
     * @var string
     */
    protected string $descriptionForFieldFormatPhone;
    /**
     * Описание для поля Email
     *
     * @wpOptionsName email_descript
     *
     * @var string
     */
    protected string $descriptionForFieldEmail;
    /**
     * Описание для поля Комментарий
     *
     * @wpOptionsName dopik_descript
     *
     * @var string
     */
    protected string $descriptionForFieldComment;
    /**
     * Описание для поля Файлы
     *
     * @wpOptionsName upload_input_file_descript
     *
     * @var string
     */
    protected string $descriptionForFieldFiles;
    /**
     * Название кнопки на форме быстрого заказа
     *
     * @wpOptionsName butform_descript
     *
     * @var string
     */
    protected string $descriptionForFieldOrderButton;
    /**
     * Является обязательным поле Имя
     *
     * @wpOptionsName fio_verifi
     *
     * @var boolean
     */
    protected bool $fieldNameIsRequired;
    /**
     * Является обязательным поле Телефон
     *
     * @wpOptionsName fon_verifi
     *
     * @var boolean
     */
    protected bool $fieldPhoneIsRequired;
    /**
     * Является обязательным поле Email
     *
     * @wpOptionsName email_verifi
     *
     * @var boolean
     */
    protected bool $fieldEmailIsRequired;
    /**
     * Является обязательным поле Комментарий
     *
     * @wpOptionsName dopik_verifi
     *
     * @var boolean
     */
    protected bool $fieldCommentIsRequired;
    /**
     * Является обязательным поле Файлы
     *
     * @wpOptionsName upload_input_file_verifi
     *
     * @var boolean
     */
    protected bool $fieldFilesIsRequired;
    /**
     * Маска ввода номера телефона
     *
     * @wpOptionsName fon_format_input
     *
     * @var string
     */
    protected string $phoneNumberInputMask = '';
    /**
     * Включить взаимодействие с механизмом остатков в WooCommerce
     *
     * @wpOptionsName woo_stock_status_enable
     *
     * @var boolean
     */
    protected bool $enableWorkWithRemainingItems;
    /**
     * Описание для кнопки предзаказа (при упралвении остатками)
     *
     * @wpOptionsName woo_stock_status_button_text
     *
     * @var string
     */
    protected string $descriptionOfPreOrderButton;
    /**
     * Сообщение в форме после отправки
     *
     * @wpOptionsName success
     *
     * @var string
     */
    protected string $submittingFormMessageSuccess;
    /**
     * Действие после отправки
     *
     * @wpOptionsName success_action
     *
     * @var integer
     */
    protected int $actionAfterSubmittingForm = 0;
    /**
     * Закрыть форму через N сек.
     *
     * @wpOptionsName success_action_close
     *
     * @var integer
     */
    protected int $secondsBeforeClosingForm = 0;
    /**
     * Доп. сообщение после отправки
     *
     * @wpOptionsName success_action_message
     *
     * @var string
     */
    protected string $messageAfterSubmittingForm = '';
    /**
     * URL адрес перенаправления (произвольный)
     *
     * @wpOptionsName success_action_redirect
     *
     * @var ?string
     */
    protected ?string $urlRedirectAddress;
    /**
     * Стиль формы
     *
     * @wpOptionsName form_style_color
     *
     * @var integer
     */
    protected int $formStyle = 1;
    /**
     * Лимит на отправку формы
     *
     * @wpOptionsName time_limit_send_form
     *
     * @var integer
     */
    protected int $formSubmissionLimit = 10;
    /**
     * Сообщение при лимите
     *
     * @wpOptionsName time_limit_message
     *
     * @var string
     */
    protected string $formSubmissionLimitMessage;
    /**
     * Согласие на обработку
     *
     * @wpOptionsName conset_personal_data_enabled
     *
     * @var boolean
     */
    protected bool $consentToProcessing;
    /**
     * Согласие на обработку текст
     *
     * @wpOptionsName conset_personal_data_text
     *
     * @var string
     */
    protected string $descriptionConsentToProcessing;
    /**
     * Использовать Капчу
     *
     * @wpOptionsName recaptcha_order_form
     *
     * @var boolean
     */
    protected bool $recaptchaEnabled;
    /**
     * Плагин предоставляющий капчу
     *
     * @wpOptionsName recaptcha_order_form
     *
     * @var string
     */
    protected string $captchaProvider;
    /**
     * Добавить css в форму
     *
     * @wpOptionsName style_insert_html
     *
     * @var boolean
     */
    protected bool $styleInsertHtml;
    /**
     * Статус заказа WooCommerce при оформлении через форму плагина
     *
     * @wpOptionsName woo_commerce_order_status
     *
     * @var string
     */
    protected string $wooCommerceOrderStatus;

    /**
     * Настройки из WordPress в св-ва
     *
     * @param array<string, mixed> $options
     */
    public function __construct(array $options)
    {
        $this->pluginWorkMode = intval($options['plugin_work_mode'] ?? 0);
        $this->enableButton = boolval($options['enable_button'] ?? false);
        $this->enableButtonShortcode = boolval(
            $options['enable_button_shortcod'] ?? false
        );
        $this->nameButton = $options['namebutton'] ?? null;
        $this->positionButton = $options['positionbutton'] ?? ButtonPosition::WOOCOMMERCE_AFTER_ADD_TO_CART_BUTTON;
        $this->positionButtonOutStock = $options['positionbutton_out_stock'] ??
            '';
        $this->addAnOrderToWooCommerce = boolval(
            $options['add_tableorder_woo'] ?? false
        );
        $this->enableButtonCategory = boolval(
            $options['enable_button_category'] ?? false
        );
        $this->buttonPositionInCategory = $options['positionbutton_category'] ??
            ButtonPosition::WOOCOMMERCE_AFTER_SHOP_LOOP_ITEM;
        $this->enableProductInformation = boolval(
            $options['infotovar_chek'] ?? false
        );
        $this->enableFieldWithName = boolval($options['fio_chek'] ?? false);
        $this->enableFieldWithPhone = boolval($options['fon_chek'] ?? false);
        $this->enableFieldWithEmail = boolval($options['email_chek'] ?? false);
        $this->enableFieldWithComment = boolval(
            $options['dopik_chek'] ?? false
        );
        $this->enableFieldWithQuantity = boolval(
            $options['add_quantity_form'] ?? false
        );
        $this->enableFieldWithFiles = boolval(
            $options['upload_input_file_chek'] ?? false
        );
        $this->descriptionForFieldName = $options['fio_descript'] ?? '';
        $this->descriptionForFieldPhone = $options['fon_descript'] ?? '';
        $this->descriptionForFieldFormatPhone = $options['fon_format'] ?? '';
        $this->descriptionForFieldEmail = $options['email_descript'] ?? '';
        $this->descriptionForFieldComment = $options['dopik_descript'] ?? '';
        $this->descriptionForFieldFiles = $options['upload_input_file_descript']
            ?? '';
        $this->descriptionForFieldOrderButton = $options['butform_descript'] ??
            '';
        $this->fieldNameIsRequired = boolval($options['fio_verifi'] ?? false);
        $this->fieldPhoneIsRequired = boolval($options['fon_verifi'] ?? false);
        $this->fieldEmailIsRequired = boolval(
            $options['email_verifi'] ?? false
        );
        $this->fieldCommentIsRequired = boolval(
            $options['dopik_verifi'] ?? false
        );
        $this->fieldFilesIsRequired = boolval(
            $options['upload_input_file_verifi'] ?? false
        );
        $this->phoneNumberInputMask = $options['fon_format_input'] ?? '';
        $this->enableWorkWithRemainingItems = boolval(
            $options['woo_stock_status_enable'] ?? false
        );
        $this->descriptionOfPreOrderButton
            = $options['woo_stock_status_button_text'] ?? '';
        $this->submittingFormMessageSuccess = $options['success'] ?? '';
        $this->actionAfterSubmittingForm = intval($options['success_action'] ?? 0);
        $this->secondsBeforeClosingForm = intval(
            $options['success_action_close'] ?? 0
        );
        $this->messageAfterSubmittingForm = $options['success_action_message']
            ?? '';
        $this->urlRedirectAddress = $options['success_action_redirect'] ?? null;
        $this->formStyle = intval($options['form_style_color'] ?? 1);
        $this->formSubmissionLimit = intval(
            $options['time_limit_send_form'] ?? 10
        );
        $this->formSubmissionLimitMessage = $options['time_limit_message'] ??
            '';
        $this->consentToProcessing = boolval(
            $options['conset_personal_data_enabled'] ?? false
        );
        $this->descriptionConsentToProcessing
            = $options['conset_personal_data_text'] ?? '';
        $this->recaptchaEnabled = boolval(
            $options['recaptcha_order_form'] ?? false
        );
        $this->captchaProvider = $options['recaptcha_order_form'] ?? '';
        $this->styleInsertHtml = boolval(
            $options['style_insert_html'] ?? false
        );

        $this->wooCommerceOrderStatus = $options['woo_commerce_order_status'] ?? OrderStatus::WITHOUT_STATUS;
    }

    /**
     * @return int
     */
    public function getPluginWorkMode(): int
    {
        return $this->pluginWorkMode;
    }

    /**
     * @param int $pluginWorkMode
     *
     * @return General
     */
    public function setPluginWorkMode(int $pluginWorkMode): General
    {
        $this->pluginWorkMode = $pluginWorkMode;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnableButton(): bool
    {
        return $this->enableButton;
    }

    /**
     * @param bool $enableButton
     *
     * @return General
     */
    public function setEnableButton(bool $enableButton): General
    {
        $this->enableButton = $enableButton;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnableButtonShortcode(): bool
    {
        return $this->enableButtonShortcode;
    }

    /**
     * @param bool $enableButtonShortcode
     *
     * @return General
     */
    public function setEnableButtonShortcode(
        bool $enableButtonShortcode
    ): General {
        $this->enableButtonShortcode = $enableButtonShortcode;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNameButton(): ?string
    {
        return $this->nameButton;
    }

    /**
     * @param string|null $nameButton
     *
     * @return General
     */
    public function setNameButton(?string $nameButton): General
    {
        $this->nameButton = $nameButton;
        return $this;
    }

    /**
     * @return string
     */
    public function getPositionButton(): string
    {
        return $this->positionButton;
    }

    /**
     * @param string $positionButton
     *
     * @return General
     */
    public function setPositionButton($positionButton)
    {
        $this->positionButton = $positionButton;
        return $this;
    }

    /**
     * @return string
     */
    public function getPositionButtonOutStock(): string
    {
        return $this->positionButtonOutStock;
    }

    /**
     * @param string $positionButtonOutStock
     *
     * @return General
     */
    public function setPositionButtonOutStock($positionButtonOutStock): General
    {
        $this->positionButtonOutStock = $positionButtonOutStock;
        return $this;
    }





    /**
     * @return bool
     */
    public function isAddAnOrderToWooCommerce(): bool
    {
        return $this->addAnOrderToWooCommerce;
    }

    /**
     * @param bool $addAnOrderToWooCommerce
     *
     * @return General
     */
    public function setAddAnOrderToWooCommerce(
        bool $addAnOrderToWooCommerce
    ): General {
        $this->addAnOrderToWooCommerce = $addAnOrderToWooCommerce;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnableButtonCategory(): bool
    {
        return $this->enableButtonCategory;
    }

    /**
     * @param bool $enableButtonCategory
     *
     * @return General
     */
    public function setEnableButtonCategory(bool $enableButtonCategory): General
    {
        $this->enableButtonCategory = $enableButtonCategory;
        return $this;
    }

    /**
     * @return string
     */
    public function getButtonPositionInCategory(): string
    {
        return $this->buttonPositionInCategory;
    }

    /**
     * @param string $buttonPositionInCategory
     *
     * @return General
     */
    public function setButtonPositionInCategory($buttonPositionInCategory): General
    {
        $this->buttonPositionInCategory = $buttonPositionInCategory;
        return $this;
    }



    /**
     * @return bool
     */
    public function isEnableProductInformation(): bool
    {
        return $this->enableProductInformation;
    }

    /**
     * @param bool $enableProductInformation
     *
     * @return General
     */
    public function setEnableProductInformation(
        bool $enableProductInformation
    ): General {
        $this->enableProductInformation = $enableProductInformation;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnableFieldWithName(): bool
    {
        return $this->enableFieldWithName;
    }

    /**
     * @param bool $enableFieldWithName
     *
     * @return General
     */
    public function setEnableFieldWithName(bool $enableFieldWithName): General
    {
        $this->enableFieldWithName = $enableFieldWithName;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnableFieldWithPhone(): bool
    {
        return $this->enableFieldWithPhone;
    }

    /**
     * @param bool $enableFieldWithPhone
     *
     * @return General
     */
    public function setEnableFieldWithPhone(bool $enableFieldWithPhone): General
    {
        $this->enableFieldWithPhone = $enableFieldWithPhone;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnableFieldWithEmail(): bool
    {
        return $this->enableFieldWithEmail;
    }

    /**
     * @param bool $enableFieldWithEmail
     *
     * @return General
     */
    public function setEnableFieldWithEmail(bool $enableFieldWithEmail): General
    {
        $this->enableFieldWithEmail = $enableFieldWithEmail;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnableFieldWithComment(): bool
    {
        return $this->enableFieldWithComment;
    }

    /**
     * @param bool $enableFieldWithComment
     *
     * @return General
     */
    public function setEnableFieldWithComment(
        bool $enableFieldWithComment
    ): General {
        $this->enableFieldWithComment = $enableFieldWithComment;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnableFieldWithQuantity(): bool
    {
        return $this->enableFieldWithQuantity;
    }

    /**
     * @param bool $enableFieldWithQuantity
     *
     * @return General
     */
    public function setEnableFieldWithQuantity(
        bool $enableFieldWithQuantity
    ): General {
        $this->enableFieldWithQuantity = $enableFieldWithQuantity;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnableFieldWithFiles(): bool
    {
        return $this->enableFieldWithFiles;
    }

    /**
     * @param bool $enableFieldWithFiles
     *
     * @return General
     */
    public function setEnableFieldWithFiles(bool $enableFieldWithFiles): General
    {
        $this->enableFieldWithFiles = $enableFieldWithFiles;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescriptionForFieldName(): string
    {
        return $this->descriptionForFieldName;
    }

    /**
     * @param string $descriptionForFieldName
     *
     * @return General
     */
    public function setDescriptionForFieldName(
        $descriptionForFieldName
    ): General {
        $this->descriptionForFieldName = $descriptionForFieldName;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescriptionForFieldPhone(): string
    {
        return $this->descriptionForFieldPhone;
    }

    /**
     * @param string $descriptionForFieldPhone
     *
     * @return General
     */
    public function setDescriptionForFieldPhone(
        $descriptionForFieldPhone
    ): General {
        $this->descriptionForFieldPhone = $descriptionForFieldPhone;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescriptionForFieldFormatPhone(): string
    {
        return $this->descriptionForFieldFormatPhone;
    }

    /**
     * @param string $descriptionForFieldFormatPhone
     *
     * @return General
     */
    public function setDescriptionForFieldFormatPhone(
        $descriptionForFieldFormatPhone
    ): General {
        $this->descriptionForFieldFormatPhone = $descriptionForFieldFormatPhone;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescriptionForFieldEmail(): string
    {
        return $this->descriptionForFieldEmail;
    }

    /**
     * @param string $descriptionForFieldEmail
     *
     * @return General
     */
    public function setDescriptionForFieldEmail(
        $descriptionForFieldEmail
    ): General {
        $this->descriptionForFieldEmail = $descriptionForFieldEmail;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescriptionForFieldComment(): string
    {
        return $this->descriptionForFieldComment;
    }

    /**
     * @param string $descriptionForFieldComment
     *
     * @return General
     */
    public function setDescriptionForFieldComment(
        $descriptionForFieldComment
    ): General {
        $this->descriptionForFieldComment = $descriptionForFieldComment;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescriptionForFieldFiles(): string
    {
        return $this->descriptionForFieldFiles;
    }

    /**
     * @param string $descriptionForFieldFiles
     *
     * @return General
     */
    public function setDescriptionForFieldFiles($descriptionForFieldFiles)
    {
        $this->descriptionForFieldFiles = $descriptionForFieldFiles;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescriptionForFieldOrderButton(): string
    {
        return $this->descriptionForFieldOrderButton;
    }

    /**
     * @param string $descriptionForFieldOrderButton
     *
     * @return General
     */
    public function setDescriptionForFieldOrderButton(
        $descriptionForFieldOrderButton
    ): General {
        $this->descriptionForFieldOrderButton = $descriptionForFieldOrderButton;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFieldNameIsRequired(): bool
    {
        return $this->fieldNameIsRequired;
    }

    /**
     * @param bool $fieldNameIsRequired
     *
     * @return General
     */
    public function setFieldNameIsRequired(bool $fieldNameIsRequired): General
    {
        $this->fieldNameIsRequired = $fieldNameIsRequired;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFieldPhoneIsRequired(): bool
    {
        return $this->fieldPhoneIsRequired;
    }

    /**
     * @param bool $fieldPhoneIsRequired
     *
     * @return General
     */
    public function setFieldPhoneIsRequired(bool $fieldPhoneIsRequired): General
    {
        $this->fieldPhoneIsRequired = $fieldPhoneIsRequired;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFieldEmailIsRequired(): bool
    {
        return $this->fieldEmailIsRequired;
    }

    /**
     * @param bool $fieldEmailIsRequired
     *
     * @return General
     */
    public function setFieldEmailIsRequired(bool $fieldEmailIsRequired): General
    {
        $this->fieldEmailIsRequired = $fieldEmailIsRequired;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFieldCommentIsRequired(): bool
    {
        return $this->fieldCommentIsRequired;
    }

    /**
     * @param bool $fieldCommentIsRequired
     *
     * @return General
     */
    public function setFieldCommentIsRequired(
        bool $fieldCommentIsRequired
    ): General {
        $this->fieldCommentIsRequired = $fieldCommentIsRequired;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFieldFilesIsRequired(): bool
    {
        return $this->fieldFilesIsRequired;
    }

    /**
     * @param bool $fieldFilesIsRequired
     *
     * @return General
     */
    public function setFieldFilesIsRequired(bool $fieldFilesIsRequired): General
    {
        $this->fieldFilesIsRequired = $fieldFilesIsRequired;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneNumberInputMask(): string
    {
        return $this->phoneNumberInputMask;
    }

    /**
     * @param string $phoneNumberInputMask
     *
     * @return General
     */
    public function setPhoneNumberInputMask($phoneNumberInputMask): General
    {
        $this->phoneNumberInputMask = $phoneNumberInputMask;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnableWorkWithRemainingItems(): bool
    {
        return $this->enableWorkWithRemainingItems;
    }

    /**
     * @param bool $enableWorkWithRemainingItems
     *
     * @return General
     */
    public function setEnableWorkWithRemainingItems(
        bool $enableWorkWithRemainingItems
    ): General {
        $this->enableWorkWithRemainingItems = $enableWorkWithRemainingItems;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescriptionOfPreOrderButton(): string
    {
        return $this->descriptionOfPreOrderButton;
    }

    /**
     * @param string $descriptionOfPreOrderButton
     *
     * @return General
     */
    public function setDescriptionOfPreOrderButton($descriptionOfPreOrderButton): General
    {
        $this->descriptionOfPreOrderButton = $descriptionOfPreOrderButton;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubmittingFormMessageSuccess(): string
    {
        return $this->submittingFormMessageSuccess;
    }

    /**
     * @param string $submittingFormMessageSuccess
     *
     * @return General
     */
    public function setSubmittingFormMessageSuccess(
        $submittingFormMessageSuccess
    ): General {
        $this->submittingFormMessageSuccess = $submittingFormMessageSuccess;
        return $this;
    }

    /**
     * @return int
     */
    public function getActionAfterSubmittingForm(): int
    {
        return $this->actionAfterSubmittingForm;
    }

    /**
     * @param int $actionAfterSubmittingForm
     *
     * @return General
     */
    public function setActionAfterSubmittingForm(int $actionAfterSubmittingForm): General
    {
        $this->actionAfterSubmittingForm = $actionAfterSubmittingForm;
        return $this;
    }

    /**
     * @return int
     */
    public function getSecondsBeforeClosingForm(): int
    {
        return $this->secondsBeforeClosingForm;
    }

    /**
     * @param int $secondsBeforeClosingForm
     *
     * @return General
     */
    public function setSecondsBeforeClosingForm(
        int $secondsBeforeClosingForm
    ): General {
        $this->secondsBeforeClosingForm = $secondsBeforeClosingForm;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessageAfterSubmittingForm(): string
    {
        return $this->messageAfterSubmittingForm;
    }

    /**
     * @param string $messageAfterSubmittingForm
     *
     * @return General
     */
    public function setMessageAfterSubmittingForm(string $messageAfterSubmittingForm): General
    {
        $this->messageAfterSubmittingForm = $messageAfterSubmittingForm;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrlRedirectAddress(): ?string
    {
        return $this->urlRedirectAddress;
    }

    /**
     * @param string|null $urlRedirectAddress
     *
     * @return General
     */
    public function setUrlRedirectAddress(?string $urlRedirectAddress): General
    {
        $this->urlRedirectAddress = $urlRedirectAddress;
        return $this;
    }

    /**
     * @return int
     */
    public function getFormStyle(): int
    {
        return $this->formStyle;
    }

    /**
     * @param int $formStyle
     *
     * @return General
     */
    public function setFormStyle(int $formStyle): General
    {
        $this->formStyle = $formStyle;
        return $this;
    }


    /**
     * @return int
     */
    public function getFormSubmissionLimit(): int
    {
        return $this->formSubmissionLimit;
    }

    /**
     * @param int $formSubmissionLimit
     *
     * @return General
     */
    public function setFormSubmissionLimit(int $formSubmissionLimit): General
    {
        $this->formSubmissionLimit = $formSubmissionLimit;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormSubmissionLimitMessage(): string
    {
        return $this->formSubmissionLimitMessage;
    }

    /**
     * @param string $formSubmissionLimitMessage
     *
     * @return General
     */
    public function setFormSubmissionLimitMessage(string $formSubmissionLimitMessage): General
    {
        $this->formSubmissionLimitMessage = $formSubmissionLimitMessage;
        return $this;
    }

    /**
     * @return bool
     */
    public function isConsentToProcessing(): bool
    {
        return $this->consentToProcessing;
    }

    /**
     * @param bool $consentToProcessing
     *
     * @return General
     */
    public function setConsentToProcessing(bool $consentToProcessing): General
    {
        $this->consentToProcessing = $consentToProcessing;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescriptionConsentToProcessing(): string
    {
        return $this->descriptionConsentToProcessing;
    }

    /**
     * @param string $descriptionConsentToProcessing
     *
     * @return General
     */
    public function setDescriptionConsentToProcessing(
        $descriptionConsentToProcessing
    ): General {
        $this->descriptionConsentToProcessing = $descriptionConsentToProcessing;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRecaptchaEnabled(): bool
    {
        return $this->recaptchaEnabled;
    }

    /**
     * @param bool $recaptchaEnabled
     *
     * @return General
     */
    public function setRecaptchaEnabled(bool $recaptchaEnabled): General
    {
        $this->recaptchaEnabled = $recaptchaEnabled;
        return $this;
    }

    /**
     * @return string
     */
    public function getCaptchaProvider(): string
    {
        return $this->captchaProvider;
    }

    /**
     * @param string $captchaProvider
     *
     * @return General
     */
    public function setCaptchaProvider(string $captchaProvider): General
    {
        $this->captchaProvider = $captchaProvider;
        return $this;
    }

    /**
     * @return bool
     */
    public function isStyleInsertHtml(): bool
    {
        return $this->styleInsertHtml;
    }

    /**
     * @param bool $styleInsertHtml
     *
     * @return General
     */
    public function setStyleInsertHtml(bool $styleInsertHtml): General
    {
        $this->styleInsertHtml = $styleInsertHtml;
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

    /**
     * Get wooCommerceOrderStatus
     *
     * @return string
     */
    public function getWooCommerceOrderStatus(): string
    {
        return $this->wooCommerceOrderStatus;
    }

    /**
     * @param string $wooCommerceOrderStatus
     *
     * @return General
     */
    public function setWooCommerceOrderStatus(string $wooCommerceOrderStatus): General
    {
        $this->wooCommerceOrderStatus = $wooCommerceOrderStatus;
        return $this;
    }
    
    /**
     * Поля которые можно переводить
     *
     * @return array
     */
    public function getTextsForTranslation(): array
    {
        return [
            $this->getDescriptionForFieldComment(),
            $this->getDescriptionConsentToProcessing(),
            $this->getDescriptionForFieldEmail(),
            $this->getDescriptionForFieldFiles(),
            $this->getDescriptionForFieldFormatPhone(),
            $this->getDescriptionForFieldName(),
            $this->getDescriptionOfPreOrderButton(),
            $this->getNameButton(),
            $this->getDescriptionForFieldOrderButton(),
            $this->getMessageAfterSubmittingForm(),
            $this->getSubmittingFormMessageSuccess(),
            $this->getFormSubmissionLimitMessage(),
        ];
    }
}
