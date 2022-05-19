<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Options;

/**
 * Class General
 */
class General implements OptionsInterface
{
    use UtilsTrait;

    /**
     * Режим работы плагина
     * @wpOptionsName plugin_work_mode
     *
     * @var string|null
     */
    protected ?string $pluginWorkMode;
    
    /**
     * Включить/отключить кнопку
     * @wpOptionsName enable_button
     *
     * @var bool
     */
    protected bool $enableButton;
    
    /**
     * Включить/отключить кнопку шорткод
     * @wpOptionsName enable_button_shortcod
     *
     * @var bool
     */
    protected bool $enableButtonShortcode;
    
    /**
     * Имя кнопки
     * @wpOptionsName namebutton
     *
     * @var string|null
     */
    protected ?string $nameButton;
    
    /**
     * Позиция кнопки
     * @wpOptionsName positionbutton
     *
     * @var string|null
     */
    protected ?string $positionButton;
    
    /**
     * Позиция кнопки для товара которого нет в наличие
     * @wpOptionsName positionbutton_out_stock
     *
     * @var string|null
     */
    protected ?string $positionButtonOutStock;
    
    /**
     * Записывать заказы в таблицу WooCommerce
     * @wpOptionsName add_tableorder_woo
     *
     * @var bool
     */
    protected bool $addAnOrderToWooCommerce;
    
    /**
     * Включить кнопку в категории
     * @wpOptionsName enable_button_category
     *
     * @var bool
     */
    protected bool $enableButtonCategory;
    
    /**
     * Позиция кнопки в категории
     * @wpOptionsName positionbutton_category
     *
     * @var string|null
     */
    protected ?string $buttonPositionInCategory;
    
    /**
     * Включить информацию о товаре на форму
     * @wpOptionsName infotovar_chek
     *
     * @var bool
     */
    protected bool $enableProductInformation;
    
    /**
     * Включить поле с именем на форму
     * @wpOptionsName fio_chek
     *
     * @var bool
     */
    protected bool $enableFieldWithName;
    
    /**
     * Включить поле с телефоном на форму
     * @wpOptionsName fon_chek
     *
     * @var bool
     */
    protected bool $enableFieldWithPhone;
    
    /**
     * Включить поле с email на форму
     * @wpOptionsName email_chek
     *
     * @var bool
     */
    protected bool $enableFieldWithEmail;
    
    /**
     * Включить поле с комментарием на форму
     * @wpOptionsName dopik_chek
     *
     * @var bool
     */
    protected bool $enableFieldWithComment;
    
    /**
     * Включить поле с количеством на форму
     * @wpOptionsName add_quantity_form
     *
     * @var bool
     */
    protected bool $enableFieldWithQuantity;
    
    /**
     * Включить поле с файлами на форму
     * @wpOptionsName upload_input_file_chek
     *
     * @var bool
     */
    protected bool $enableFieldWithFiles;
    
    /**
     * Описание для поля Имя
     * @wpOptionsName fio_descript
     *
     * @var string
     */
    protected string $descriptionForFieldName;
    
    /**
     * Описание для поля Телефон
     * @wpOptionsName fon_descript
     *
     * @var string
     */
    protected string $descriptionForFieldPhone;
    
    /**
     * Описание для поля Формат телефон
     * @wpOptionsName fon_format
     *
     * @var string
     */
    protected string $descriptionForFieldFormatPhone;
    
    /**
     * Описание для поля Email
     * @wpOptionsName email_descript
     *
     * @var string
     */
    protected string $descriptionForFieldEmail;
    
    /**
     * Описание для поля Комментарий
     * @wpOptionsName dopik_descript
     *
     * @var string
     */
    protected string $descriptionForFieldComment;
    
    /**
     * Описание для поля Файлы
     * @wpOptionsName upload_input_file_descript
     *
     * @var string
     */
    protected string $descriptionForFieldFiles;
    
    /**
     * Название кнопки на форме быстрого заказа
     * @wpOptionsName butform_descript
     *
     * @var string
     */
    protected string $descriptionForFieldOrderButton;
    
    /**
     * Является обязательным поле Имя
     * @wpOptionsName fio_verifi
     *
     * @var bool
     */
    protected bool $fieldNameIsRequired;
    
    /**
     * Является обязательным поле Телефон
     * @wpOptionsName fon_verifi
     *
     * @var bool
     */
    protected bool $fieldPhoneIsRequired;
    
    /**
     * Является обязательным поле Email
     * @wpOptionsName email_verifi
     *
     * @var bool
     */
    protected bool $fieldEmailIsRequired;
    
    /**
     * Является обязательным поле Комментарий
     * @wpOptionsName dopik_verifi
     *
     * @var bool
     */
    protected bool $fieldCommentIsRequired;
    
    /**
     * Является обязательным поле Файлы
     * @wpOptionsName upload_input_file_verifi
     *
     * @var bool
     */
    protected bool $fieldFilesIsRequired;
    
    /**
     * Маска ввода номера телефона
     * @wpOptionsName fon_format
     *
     * @var string
     */
    protected string $phoneNumberInputMask;
    
    /**
     * Включить взаимодействие с механизмом остатков в WooCommerce
     * @wpOptionsName woo_stock_status_enable
     *
     * @var bool
     */
    protected bool $enableWorkWithRemainingItems;
    
    /**
     * Описание для кнопки предзаказа (при упралвении остатками)
     * @wpOptionsName woo_stock_status_button_text
     *
     * @var string
     */
    protected string $descriptionOfPreOrderButton;
    
    /**
     * Сообщение в форме после отправки
     * @wpOptionsName success
     *
     * @var string
     */
    protected string $submittingFormMessageSuccess;
    
    /**
     * Действие после отправки
     * @wpOptionsName success_action
     *
     *
     * @var ?string
     */
    protected ?string $actionAfterSubmittingForm;
    
    /**
     * Закрыть форму через N сек.
     * @wpOptionsName success_action_close
     *
     * @var int
     */
    protected int $secondsBeforeClosingForm;
    
    /**
     * Доп. сообщение после отправки
     * @wpOptionsName success_action_message
     *
     * @var string
     */
    protected string $messageAfterSubmittingForm;
    
    /**
     * URL адрес перенаправления (произвольный)
     * @wpOptionsName success_action_redirect
     *
     * @var ?string
     */
    protected ?string $urlRedirectAddress;
    
    /**
     * Стиль формы
     * @wpOptionsName form_style_color
     *
     * @var string
     */
    protected string $formStyle;
    
    /**
     * Лимит на отправку формы
     * @wpOptionsName time_limit_send_form
     *
     * @var int
     */
    protected int $formSubmissionLimit;
    
    /**
     * Сообщение при лимите
     * @wpOptionsName time_limit_message
     *
     * @var string
     */
    protected string $formSubmissionLimitMessage;
    
    /**
     * Согласие на обработку
     * @wpOptionsName conset_personal_data_enabled
     *
     * @var bool
     */
    protected bool $consentToProcessing;
    
    /**
     * Согласие на обработку текст
     * @wpOptionsName conset_personal_data_text
     *
     * @var string
     */
    protected string $descriptionConsentToProcessing;
    
    /**
     * Использовать Капчу
     * @wpOptionsName recaptcha_order_form
     *
     * @var bool
     */
    protected bool $recaptchaEnabled;
    
    /**
     * Плагин предоставляющий капчу
     * @wpOptionsName recaptcha_order_form
     *
     * @var string
     */
    protected string $captchaProvider;
    
    /**
     * Добавить css в форму
     * @wpOptionsName style_insert_html
     *
     * @var bool
     */
    protected bool $styleInsertHtml;
    
    /**
     * Настройки из WordPress в св-ва
     *
     * @param array<string, mixed> $options
     */
    public function __construct(array $options)
    {
        $this->pluginWorkMode = $options['plugin_work_mode'] ?? null;
        $this->enableButton = boolval($options['enable_button'] ?? false);
        $this->enableButtonShortcode = boolval($options['enable_button_shortcod'] ?? false);
        $this->nameButton = $options['namebutton'] ?? null;
        $this->positionButton = $options['positionbutton'] ?? null;
        $this->positionButtonOutStock = $options['positionbutton_out_stock'] ?? null;
        $this->addAnOrderToWooCommerce = boolval($options['add_tableorder_woo'] ?? false);
        $this->enableButtonCategory = boolval($options['enable_button_category'] ?? false);
        $this->buttonPositionInCategory = $options['positionbutton_category'] ?? null;
        $this->enableProductInformation = boolval($options['infotovar_chek'] ?? false);
        $this->enableFieldWithName = boolval($options['fio_chek'] ?? false);
        $this->enableFieldWithPhone = boolval($options['fon_chek'] ?? false);
        $this->enableFieldWithEmail = boolval($options['email_chek'] ?? false);
        $this->enableFieldWithComment = boolval($options['dopik_chek'] ?? false);
        $this->enableFieldWithQuantity = boolval($options['add_quantity_form'] ?? false);
        $this->enableFieldWithFiles = boolval($options['upload_input_file_chek'] ?? false);
        $this->descriptionForFieldName = $options['fio_descript'] ?? '';
        $this->descriptionForFieldPhone = $options['fon_descript'] ?? '';
        $this->descriptionForFieldFormatPhone = $options['fon_format'] ?? '';
        $this->descriptionForFieldEmail = $options['email_descript'] ?? '';
        $this->descriptionForFieldComment = $options['dopik_descript'] ?? '';
        $this->descriptionForFieldFiles = $options['upload_input_file_descript'] ?? '';
        $this->descriptionForFieldOrderButton = $options['butform_descript'] ?? '';
        $this->fieldNameIsRequired = boolval($options['fio_verifi'] ?? false);
        $this->fieldPhoneIsRequired = boolval($options['fon_verifi'] ?? false);
        $this->fieldEmailIsRequired = boolval($options['email_verifi'] ?? false);
        $this->fieldCommentIsRequired = boolval($options['dopik_verifi'] ?? false);
        $this->fieldFilesIsRequired = boolval($options['upload_input_file_verifi'] ?? false);
        $this->phoneNumberInputMask = $options['fon_format'] ?? '';
        $this->enableWorkWithRemainingItems = boolval($options['woo_stock_status_enable'] ?? false);
        $this->descriptionOfPreOrderButton = $options['woo_stock_status_button_text'] ?? '';
        $this->submittingFormMessageSuccess = $options['success'] ?? '';
        $this->actionAfterSubmittingForm = $options['success_action'] ?? null;
        $this->secondsBeforeClosingForm = intval($options['success_action_close'] ?? 5);
        $this->messageAfterSubmittingForm = $options['success_action_message'] ?? '';
        $this->urlRedirectAddress = $options['success_action_redirect'] ?? null;
        $this->formStyle = $options['form_style_color'] ?? null;
        $this->formSubmissionLimit = intval($options['time_limit_send_form'] ?? 10);
        $this->formSubmissionLimitMessage = $options['time_limit_message'] ?? '';
        $this->consentToProcessing = boolval($options['conset_personal_data_enabled'] ?? false);
        $this->descriptionConsentToProcessing = $options['conset_personal_data_text'] ?? '';
        $this->recaptchaEnabled = boolval($options['recaptcha_order_form'] ?? false);
        $this->captchaProvider = $options['recaptcha_order_form'] ?? null;
        $this->styleInsertHtml = boolval($options['style_insert_html'] ?? false);
    }
    
    
    
}