<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Utils;

/**
 * Class Hooks
 *
 * @package Coderun\BuyOneClick\Utils
 */
class Hooks
{
    /**
     * Плагин загружен
     */
    public static function load()
    {
        do_action('buy_click_load');
    }

    /**
     * Вызывается после создания нового заказа
     *
     * @param array $arResult результат функции с заказом
     * @param array $arLog    лог(журнал
     *                        плагина)
     */
    public static function buyClickNewrder($arResult, $arLog)
    {
        do_action('buy_click_new_order', $arResult, $arLog);
    }

    /**
     * Вызывается после сохранения в таблицу данных о заказе
     */
    public static function saveOrderToTable($order_id)
    {
        do_action('buy_click_save_order_to_table', $order_id);
    }

    /**
     * Размер загружаемого файла
     *
     * @param $size
     *
     * @return mixed|void
     */
    public static function filterSizeOfUploadedFile($size)
    {
        return apply_filters('coderun_oneclickwoo_file_valid_size', $size);
    }

    /**
     * Mime типы загружаемых файлов
     *
     * @param array $types
     *
     * @return void
     */
    public static function filterMimeTypeOfDownloadedFile(array $types)
    {
        return apply_filters('coderun_oneclickwoo_file_valid_mime_types', $types);
    }

    /**
     * Расширения загружаемых файлов
     *
     * @param array $extensions
     *
     * @return mixed|void
     */
    public static function filterExtensionsOfUploadedFile(array $extensions)
    {
        return apply_filters('coderun_oneclickwoo_file_valid_extension', $extensions);
    }

    /**
     * Имя формируемых файлов
     *
     * @param string $newName
     * @param string $name
     *
     * @return mixed|void
     */
    public static function filterNameOfUploadedFile(string $newName, string $name)
    {
        return apply_filters('coderun_oneclickwoo_file_name', $newName, $name);
    }

    /**
     * Путь к папке с фалами
     *
     * @param array<string,string>{"path","url"} $path
     *
     * @return mixed|void
     */
    public static function filterPathToFileFolder(array $path)
    {
        return apply_filters('coderun_oneclickwoo_file_load_folder_path', $path);
    }
}
