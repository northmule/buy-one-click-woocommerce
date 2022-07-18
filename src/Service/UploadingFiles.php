<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Service;

use Coderun\BuyOneClick\Exceptions\UploadingFilesException;
use Coderun\BuyOneClick\SimpleDataObjects\DownloadableFile;
use Coderun\BuyOneClick\SimpleDataObjects\DownloadedFile;
use Coderun\BuyOneClick\Utils\Hooks;
use Coderun\BuyOneClick\Utils\Uuid as UuidUtils;
use Exception;

use function strlen;

/**
 * Загружает файлы из _FILES
 *
 * Class UploadingFiles
 *
 * @package Coderun\BuyOneClick\Service
 */
class UploadingFiles
{
    /**
     * Папка для загрузки файлов
     *
     * @var array
     */
    protected array $pathToDownloadsFolder = [];
    /**
     * Информация о файле
     *
     * @var array<int, DownloadableFile>
     */
    protected array $files = [];

    public function __construct()
    {
        $this->pathToDownloadsFolder = wp_upload_dir();
    }


    /**
     * Загрузка файла
     * Вернут массив message,url,error
     *
     * @return array<int, DownloadedFile>
     * @throws Exception
     */
    public function download(): array
    {
        $this->files = $this->composeFilesStructure();
        $this->checkRestriction();

        $path = rtrim($this->getLoadFolderPath()['path'], '/') . '/';
        $result = [];
        foreach ($this->files as $number => $file) {
            $newName = sprintf(
                '%s.%s',
                $this->getNewName($file->name),
                $file->extension
            );
            $savePath = $path . $newName;
            if (move_uploaded_file($file->temporaryName, $savePath)) {
                $result[$number] = new DownloadedFile(
                    [
                        'url'  => $this->pathToDownloadsFolder['url'] . '/' . $newName,
                        'path' => $savePath,
                    ]
                );
            }
        }

        return $result;
    }

    /**
     * Пересборка файла/файлов в одну структуру
     *
     * @param type $multi
     *
     * @return array<int, DownloadableFile>
     * @throws Exception
     */
    protected function composeFilesStructure(): array
    {
        $fileList = $_FILES['files'] ?? [];
        if (!$fileList) {
            return [];
        }
        $result = [];
        if ($this->isMultiForm()) {
            foreach ($fileList['name'] as $number => $value) {
                if (strlen($value) == 0) {
                    continue;
                }
                $file = new DownloadableFile(
                    [
                        'name'          => strtolower($value),
                        'type'          => $fileList['type'][$number] ?? '',
                        'temporaryName' => $fileList['tmp_name'][$number] ?? '',
                        'error'         => $fileList['error'][$number] ?? '',
                        'size'          => $fileList['size'][$number] ?? '',
                        'extension'     => $this->getExtension($value),
                    ]
                );
                $result[] = $file;
            }
        } else {
            $file = new DownloadableFile(
                [
                    'name'          => strtolower($fileList['name'] ?? ''),
                    'type'          => $fileList['type'][0] ?? '',
                    'temporaryName' => $fileList['tmp_name'][0] ?? '',
                    'error'         => $fileList['error'][0] ?? '',
                    'size'          => $fileList['size'][0] ?? '',
                    'extension'     => $this->getExtension($fileList['name'] ?? ''),
                ]
            );
            $result[] = $file;
        }

        return $result;
    }

    /**
     * Вернёт true -если используется multinput
     *
     * @return bool
     */
    protected function isMultiForm(): bool
    {
        return isset($_FILES['files']['name'][0]);
    }

    /**
     * Проверка файлов на ограничение
     *
     * @throws Exception
     */
    protected function checkRestriction()
    {
        foreach ($this->files as $file) {
            if (!in_array($file->extension, $this->getValidExtension())) {
                throw UploadingFilesException::invalidFileExtension($file->extension);
            }

            if ($file->size > $this->getValidSize()) {
                throw UploadingFilesException::invalidFileSize($file->size);
            }

            if (!in_array($file->type, $this->getValidMimeTypes())) {
                UploadingFilesException::invalidFileType($file->type);
            }
        }
    }

    /**
     * Папка для загрузки фалов
     *
     * @return array
     */
    protected function getLoadFolderPath(): array
    {
        return Hooks::filterPathToFileFolder(
            [
                'path' => $this->pathToDownloadsFolder['path'],
                'url'  => $this->pathToDownloadsFolder['url'],
            ]
        );
    }

    /**
     * Новое имя файла
     *
     * @return string
     * @throws Exception
     */
    protected function getNewName($name)
    {
        return Hooks::filterNameOfUploadedFile(
            sprintf('%s_%s', 'buy_file_', UuidUtils::uuidGenerator()),
            $name
        );
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getExtension(string $name): string
    {
        if (stripos($name, '.') === false) {
            return '';
        }
        return strtolower(pathinfo($name, PATHINFO_EXTENSION));
    }

    /**
     * Разрешенные по умолчанию разрешения файлов
     *
     * @return array
     */
    protected function getValidExtension(): array
    {
        return Hooks::filterExtensionsOfUploadedFile(['jpeg', 'jpg', 'png', 'gif', 'bmp', 'pdf', 'doc', 'ppt']);
    }

    protected function getValidMimeTypes()
    {
        $types = [
            'image/gif',
            'image/jpeg',
            'image/pjpeg',
            'image/png',
            'image/tiff',
            'image/vnd.wap.wbmp',
            'image/webp',
            'ppt',
            'text/csv',
            'text/plain',
            'text/xml',
            'application/vnd.oasis.opendocument.text',
            'application/vnd.oasis.opendocument.spreadsheet',
            'application/vnd.oasis.opendocument.presentation',
            'application/vnd.oasis.opendocument.graphics',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/x-rar-compressed',
            'application/x-tar',
            'application/pdf',
            'application/xml-dtd',
            'application/zip',
            'application/gzip',
            'application/xml',
            'application/msword',
        ];

        return Hooks::filterMimeTypeOfDownloadedFile($types);
    }

    protected function getValidSize()
    {
        return Hooks::filterSizeOfUploadedFile(10485760);
    }
}
