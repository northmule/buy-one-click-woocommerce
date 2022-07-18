<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\SimpleDataObjects;

/**
 * Загруженные и размещённый в каталоге сайта файл
 *
 * Class DownloadedFile
 *
 * @package Coderun\BuyOneClick\SimpleDataObjects
 */
class DownloadedFile extends DataTransferObject
{
    /**
     * URL до файла
     *
     * @var string
     */
    public string $url;
    /**
     * Путь до файла
     *
     * @var string
     */
    public string $path;
}
