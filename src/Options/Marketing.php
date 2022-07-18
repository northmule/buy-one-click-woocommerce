<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Options;

use Coderun\BuyOneClick\Constant\Options\Type as OptionsType;

use function strlen;

/**
 * Class Marketing
 *
 * @package Coderun\BuyOneClick\Options
 */
class Marketing extends Base
{
    protected const ROOT_KEY = OptionsType::MARKETING;

    /**
     * Событие после нажатия кнопки
     *
     * @wpOptionsName after_clicking_on_button
     *
     * @var string
     */
    protected string $afterClickingOnButton = '';
    /**
     * Событие после успешной отправки формы
     *
     * @wpOptionsName successful_form_submission
     *
     * @var string
     */
    protected string $successfulFormSubmission = '';
    /**
     * Передача данных в яндекс электронная коммерция
     *
     * @wpOptionsName transfer_data_to_yandex_commerce
     *
     * @var boolean
     */
    protected bool $transferDataToYandexCommerce = false;
    /**
     * Имя контейнера данных в яндекс электронная коммерция
     *
     * @wpOptionsName name_of_yandex_metrica_data_container
     *
     * @var string
     */
    protected string $nameOfYandexMetricaDataContainer = 'dataLayer';
    /**
     * ИД цели в яндекс электронная коммерция
     *
     * @wpOptionsName goal_id_in_yandex_e_commerce
     *
     * @var string
     */
    protected string $goalIdInYandexECommerce = '1';

    /**
     * Настройки из WordPress в св-ва
     *
     * @param array<string, mixed> $options
     */
    public function __construct(array $options)
    {
        $this->afterClickingOnButton = $options['after_clicking_on_button'] ?? '';
        $this->successfulFormSubmission = $options['successful_form_submission'] ?? '';
        $this->transferDataToYandexCommerce = boolval($options['transfer_data_to_yandex_commerce'] ?? false);
        if (strlen($options['name_of_yandex_metrica_data_container'] ?? '') > 0) {
            $this->nameOfYandexMetricaDataContainer = $options['name_of_yandex_metrica_data_container'];
        }

        $this->goalIdInYandexECommerce = $options['goal_id_in_yandex_e_commerce'] ?? '';
    }

    /**
     * @return string
     */
    public function getAfterClickingOnButton(): string
    {
        return $this->afterClickingOnButton;
    }

    /**
     * @param string $afterClickingOnButton
     *
     * @return Marketing
     */
    public function setAfterClickingOnButton(
        string $afterClickingOnButton
    ): Marketing {
        $this->afterClickingOnButton = $afterClickingOnButton;
        return $this;
    }

    /**
     * @return string
     */
    public function getSuccessfulFormSubmission(): string
    {
        return $this->successfulFormSubmission;
    }

    /**
     * @param string $successfulFormSubmission
     *
     * @return Marketing
     */
    public function setSuccessfulFormSubmission(
        string $successfulFormSubmission
    ): Marketing {
        $this->successfulFormSubmission = $successfulFormSubmission;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTransferDataToYandexCommerce(): bool
    {
        return $this->transferDataToYandexCommerce;
    }

    /**
     * @param bool $transferDataToYandexCommerce
     *
     * @return Marketing
     */
    public function setTransferDataToYandexCommerce(
        bool $transferDataToYandexCommerce
    ): Marketing {
        $this->transferDataToYandexCommerce = $transferDataToYandexCommerce;
        return $this;
    }

    /**
     * @return string
     */
    public function getNameOfYandexMetricaDataContainer(): string
    {
        return $this->nameOfYandexMetricaDataContainer;
    }

    /**
     * @param string $nameOfYandexMetricaDataContainer
     *
     * @return Marketing
     */
    public function setNameOfYandexMetricaDataContainer(
        string $nameOfYandexMetricaDataContainer
    ): Marketing {
        $this->nameOfYandexMetricaDataContainer
            = $nameOfYandexMetricaDataContainer;
        return $this;
    }

    /**
     * @return string
     */
    public function getGoalIdInYandexECommerce(): string
    {
        return $this->goalIdInYandexECommerce;
    }

    /**
     * @param string $goalIdInYandexECommerce
     *
     * @return Marketing
     */
    public function setGoalIdInYandexECommerce(
        string $goalIdInYandexECommerce
    ): Marketing {
        $this->goalIdInYandexECommerce = $goalIdInYandexECommerce;
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
