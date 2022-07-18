<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\ValueObject;

use Coderun\BuyOneClick\Options\General as GeneralOptions;

/**
 * Трансляция типа поля в его название из настроек плагина
 *
 * Class FieldNameViaType
 *
 * @package Coderun\BuyOneClick\ValueObject
 */
class FieldNameViaType
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
    /**
     * Согласие
     *
     * @var string
     */
    protected string $consent = '';
    /**
     * Файлы
     *
     * @var string
     */
    protected string $files = '';

    /**
     * @param GeneralOptions $commonOptions
     */
    public function __construct(GeneralOptions $commonOptions)
    {
        $this->userName = $commonOptions->getDescriptionForFieldName();
        $this->userEmail = $commonOptions->getDescriptionForFieldEmail();
        $this->userPhone = $commonOptions->getDescriptionForFieldPhone();
        $this->userComment = $commonOptions->getDescriptionForFieldComment();
        $this->consent = $commonOptions->getDescriptionConsentToProcessing();
        $this->files = $commonOptions->getDescriptionForFieldFiles();
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
    public function getConsent(): string
    {
        return $this->consent;
    }

    /**
     * @return string
     */
    public function getFiles(): string
    {
        return $this->files;
    }
}
