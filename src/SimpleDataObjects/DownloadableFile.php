<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\SimpleDataObjects;

/**
 * Файл загружаемый с формы
 * сырые данные
 *
 * Class DownloadableFile
 *
 * @package Coderun\BuyOneClick\SimpleDataObjects
 */
class DownloadableFile extends DataTransferObject
{
    /**
     * Имя
     *
     * @var string
     */
    public string $name;
    /**
     * Тип
     *
     * @var string
     */
    public string $type;
    /**
     * Временное имя на сервере
     *
     * @var string
     */
    public string $temporaryName;
    /**
     * Ошибки загрузки
     *
     * @var string
     */
    public string $error;
    /**
     * Размер файла
     *
     * @var string
     */
    public string $size;
    /**
     * Расширение файла
     *
     * @var string
     */
    public string $extension;
}
