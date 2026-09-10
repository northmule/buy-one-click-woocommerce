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
    public static function load(): void
    {
        do_action('coderun_oneclickwoo_load');
    }

    /**
     * Вызывается после создания нового заказа
     *
     * @param array<string, mixed> $arResult Результат функции с заказом
     * @param array<string, mixed> $arLog    Лог(журнал плагина)
     */
    public static function buyClickNewrder(array $arResult, array $arLog): void
    {
        do_action('coderun_oneclickwoo_new_order', $arResult, $arLog);
    }

    /**
     * Вызывается после сохранения в таблицу данных о заказе
     */
    public static function saveOrderToTable(int $order_id): void
    {
        do_action('coderun_oneclickwoo_save_order_to_table', $order_id);
    }

    /**
     * Размер загружаемого файла
     *
     * @param mixed $size
     *
     * @return mixed
     */
    public static function filterSizeOfUploadedFile(mixed $size): mixed
    {
        return apply_filters('coderun_oneclickwoo_file_valid_size', $size);
    }

    /**
     * Mime типы загружаемых файлов
     *
     * @param array<int, string> $types
     *
     * @return array<int, string>
     */
    public static function filterMimeTypeOfDownloadedFile(array $types): array
    {
        return apply_filters('coderun_oneclickwoo_file_valid_mime_types', $types);
    }

    /**
     * Расширения загружаемых файлов
     *
     * @param array<int, string> $extensions
     *
     * @return array<int, string>
     */
    public static function filterExtensionsOfUploadedFile(array $extensions): array
    {
        return apply_filters('coderun_oneclickwoo_file_valid_extension', $extensions);
    }

    /**
     * Имя формируемых файлов
     *
     * @param string $newName
     * @param string $name
     *
     * @return string
     */
    public static function filterNameOfUploadedFile(string $newName, string $name): string
    {
        return apply_filters('coderun_oneclickwoo_file_name', $newName, $name);
    }

    /**
     * Путь к папке с фалами
     *
     * @param array<string, string>{path: string, url: string} $path
     *
     * @return array<string, string>
     */
    public static function filterPathToFileFolder(array $path): array
    {
        return apply_filters('coderun_oneclickwoo_file_load_folder_path', $path);
    }

    /**
     * Перед тем как будет нарисована кнопка быстрого заказа в карточке товара
     * Только для вариативных товаров
     *
     * @param object|mixed $context Текущий объект
     *
     * @return void
     */
    public static function beforeDrawingOrderButtonOnlyForVariableProducts(mixed $context): void
    {
        do_action('coderun_oneclickwoo_before_drawing_order_button_only_for_variable_products', $context);
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
        return apply_filters('coderun_oneclickwoo_init_front_variables', $variables) ?? $variables;
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
        $result = apply_filters('coderun_oneclickwoo_data_about_selected_variation_from_form', $form);
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
        $result = apply_filters('coderun_oneclickwoo_get_id_of_selected_variation', $form);
        if (!is_int($result)) {
            return 0;
        }
        return $result;
    }

    /**
     * Устанавливает признак активности плагина вариативных товаров
     *
     * @param mixed $context
     *
     * @return bool
     */
    public static function filterVariationsPluginIsUsed(mixed $context): bool
    {
        $result = apply_filters('coderun_oneclickwoo_variations_plugin_is_used', $context);
        if (!is_bool($result)) {
            return false;
        }
        return $result;
    }
}
