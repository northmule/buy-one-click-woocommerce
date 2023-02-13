<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Utils;

use function is_bool;
use function is_int;
use function is_string;

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
     * @param array $arResult Результат функции с заказом
     * @param array $arLog    Лог(журнал плагина)
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
    
    /**
     * Перед тем как будет нарисована кнопка быстрого заказа в карточке товара
     * Только для вариативных товаров
     *
     * @param $context object|mixed текущий объект
     *
     * @return void
     */
    public static function beforeDrawingOrderButtonOnlyForVariableProducts($context): void
    {
        do_action('buy_click_before_drawing_order_button_only_for_variable_products', $context);
    }
    
    /**
     * Во время сборки массива пременных JS фронт
     *
     * @param array $variables
     *
     * @return array
     */
    public static function filterInitFrontVariables(array $variables): array
    {
        return apply_filters('buy_click_init_front_variables', $variables) ?? $variables;
    }
    
    /**
     * Принимает данные с формы, возвращает читабельную строку
     *
     * @param array $form
     *
     * @return string
     */
    public static function filterDataAboutSelectedVariationFromForm(array $form): string
    {
        $result = apply_filters('buy_click_data_about_selected_variation_from_form', $form);
        if (!is_string($result)) {
            return '';
        }
        return $result;
    }
    
    /**
     * ИД вариативного товара с формы
     *
     * @param array $form
     *
     * @return int
     */
    public static function filterGetIdOfSelectedVariation(array $form): int
    {
        $result = apply_filters('buy_click_get_id_of_selected_variation', $form);
        if (!is_int($result)) {
            return 0;
        }
        return $result;
    }
    
    /**
     * Устанавливает признак активности плагина вариативных товаров
     *
     * @param $context
     *
     * @return bool
     */
    public static function filterVariationsPluginIsUsed($context): bool
    {
        $result = apply_filters('buy_click_variations_plugin_is_used', $context);
        if (!is_bool($result)) {
            return false;
        }
        return $result;
    }
}
