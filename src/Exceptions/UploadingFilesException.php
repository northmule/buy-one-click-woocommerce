<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Exceptions;

use function sprintf;
use function __;

/**
 * Class UploadingFilesException
 *
 * @package Coderun\BuyOneClick\Exceptions
 */
class UploadingFilesException extends BaseException implements ExceptionInterface
{
    /**
     * @return UploadingFilesException
     */
    public static function noFilesToDownload(): UploadingFilesException
    {
        return new self(__('No file to download', 'buy-one-click-woocommerce'));
    }

    /**
     * @param string $extension
     *
     * @return UploadingFilesException
     */
    public static function invalidFileExtension(string $extension): UploadingFilesException
    {
        return new self(
            sprintf(
                /* translators: %s: file extension */
                __('Invalid file extension: %s', 'buy-one-click-woocommerce'),
                $extension
            )
        );
    }

    /**
     * @param string $size
     *
     * @return UploadingFilesException
     */
    public static function invalidFileSize(string $size): UploadingFilesException
    {
        return new self(
            sprintf(
                /* translators: %s: file size */
                __('Invalid file size: %s', 'buy-one-click-woocommerce'),
                $size
            )
        );
    }

    /**
     * @param string $type
     *
     * @return UploadingFilesException
     */
    public static function invalidFileType(string $type): UploadingFilesException
    {
        return new self(
            sprintf(
                /* translators: %s: mime type */
                __('Invalid file type: %s', 'buy-one-click-woocommerce'),
                $type
            )
        );
    }
}
