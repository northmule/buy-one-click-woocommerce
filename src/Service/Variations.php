<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Service;

use WC_Product;
use WC_Product_Variation;
use WP_Post;
use WP_Term;

/**
 * Встроенная поддержка вариативных товаров
 * Перенесена из дополнительного плагина "Buy one click WooCommerce variations"
 *
 * @package Coderun\BuyOneClick\Service
 */
class Variations
{
    /**
     * @var Variations|null
     */
    protected static ?self $_instance = null;

    /**
     * Скрипты выведены на этой странице
     */
    protected static bool $clientScriptsPrinted = false;

    /**
     * Singleton
     *
     * @return Variations
     */
    public static function getInstance(): self
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Базовая инициализация на стороне клиента
     * Выводится перед рисованием кнопки быстрого заказа для вариативных товаров
     *
     * @return void
     */
    public function init(): void
    {
        if (self::$clientScriptsPrinted) {
            return;
        }
        self::$clientScriptsPrinted = true;
        $this->printProductVariationJs();
        $this->enableThirdPartyVariationPlugins();
    }

    /**
     * Вернёт ID выбранной вариации из параметров формы
     *
     * @param array<int, array{name?: string, value?: string}> $form
     *
     * @return int
     */
    public function getVariationId(array $form): int
    {
        foreach ($form as $item) {
            if (!is_array($item) || ($item['name'] ?? '') !== 'variation_form') {
                continue;
            }
            foreach (explode('&', (string) ($item['value'] ?? '')) as $pair) {
                $parts = explode('=', $pair);
                if (stripos($parts[0] ?? '', 'variation_id') !== false) {
                    return intval($parts[1] ?? 0);
                }
            }
        }
        return 0;
    }

    /**
     * Вытаскивает выбранные значения вариаций из формы
     * Форматирует их в человекочитаемую строку для хранения в заказе
     *
     * @param array<int, array{name?: string, value?: string}> $form
     *
     * @return string
     */
    public function getVariableProductInfo(array $form): string
    {
        $result = '';
        if (empty($form)) {
            return $result;
        }
        $attributeList = [];
        $pairs = [];
        $variationId = 0;
        $productId = $this->resolveProductId();
        foreach ($form as $item) {
            if (!is_array($item)) {
                continue;
            }
            $fieldName = (string) ($item['name'] ?? '');
            if (stripos($fieldName, 'variation_form') === false) {
                continue;
            }
            $found = explode('&', (string) ($item['value'] ?? ''));
            foreach ($found as $pair) {
                $pairs[] = $pair;
                $parts = explode('=', $pair);
                $slug = $parts[0] ?? '';
                $value = $parts[1] ?? '';
                $value = $this->getAttributeValueName(rawurldecode($value), $slug);
                $attributeList[$slug] = $value;
                if (stripos($slug, 'variation_id') !== false) {
                    $variationId = intval($value);
                }
                if (stripos($slug, 'product_id') !== false) {
                    $productId = intval($value);
                }
            }
        }
        $variation = [];
        //Актуально если у вариаций записаны конкретные значения, если указаны "все" ид - вариация даст неполную информацию
        if ($variationId > 0) {
            $variation = $this->getVariationSelected($variationId);
            foreach ($variation['selected_variation'] as $name => $value) {
                if ($name === '') {
                    continue;
                }
                $result .= $name . ': ' . $this->getAttributeValueName(strval($value)) . '<br>';
            }
        }
        //Выбор значений вариаций напрямую из формы
        if (!empty($variation) && !$this->isValidAttributeList($variation['selected_variation'])) {
            $result = '';
            foreach ($attributeList as $name => $value) {
                if ((string) $name === '') {
                    continue;
                }
                if (stripos($name, 'attribute_pa_') !== false) {
                    $slug = str_replace('attribute_pa_', '', $name);
                    $taxonomy = get_taxonomy('pa_' . $slug);
                    $label = $taxonomy && isset($taxonomy->labels->singular_name) ? $taxonomy->labels->singular_name : $slug;
                    $result .= $label . ': ' . rawurldecode($value) . '<br>';
                } elseif (stripos($name, 'attribute_') !== false) {
                    $slug = str_replace('attribute_', '', $name);
                    $attributes = get_post_meta($productId, '_product_attributes', true);
                    $label = is_array($attributes) && isset($attributes[$slug]['name']) ? $attributes[$slug]['name'] : $slug;
                    $result .= $label . ': ' . rawurldecode($value) . '<br>';
                }
            }
        }
        $result .= sprintf('Цена вариаций Woocommerce: %s<br>', wc_price($variation['display_price'] ?? ''));
        //Разбор данных для плагина PPOM
        foreach ($pairs as $pair) {
            if (str_starts_with($pair, 'ppom')) {
                $result .= $this->getDataPluginPpom($productId, rawurldecode($pair));
            }
        }
        return $result;
    }

    /**
     * Данные от полей плагина N-Media WooCommerce Personalized Product Option Manager
     * Приводит их к человекувидимому виду
     *
     * @param int    $productId
     * @param string $value
     *
     * @return string
     */
    protected function getDataPluginPpom(int $productId, string $value): string
    {
        if (!class_exists('PPOM_Meta')) {
            return '';
        }
        $ppom = new \PPOM_Meta($productId);
        if (method_exists($ppom, 'get_fields')) {
            $ppomFields = $ppom->get_fields();
        } elseif (method_exists($ppom, 'fields')) {
            $ppomFields = $ppom->fields();
        } else {
            return '';
        }
        foreach ((array) $ppomFields as $dataField) {
            if (!isset($dataField['data_name'])) {
                continue;
            }
            if (stripos($value, $dataField['data_name']) !== false) {
                $segments = explode('=', $value);
                return $dataField['title'] . ': ' . end($segments) . '<br>';
            }
        }
        return '';
    }

    /**
     * Если хотя бы одно значение атрибута пусто - вернёт false
     *
     * @param array<string, mixed> $attributeList
     *
     * @return bool
     */
    protected function isValidAttributeList(array $attributeList): bool
    {
        foreach ($attributeList as $value) {
            if (trim(strval($value)) === '') {
                return false;
            }
        }
        return true;
    }

    /**
     * Подключение скриптов сторонних плагинов, работающих с вариантами товара
     *
     * @return void
     */
    protected function enableThirdPartyVariationPlugins(): void
    {
        if (in_array('WooCommerce TM Extra Product Options', $this->getActivePluginsTitles(), true)) {
            $this->printTmExtraProductOptionsJs();
        }
    }

    /**
     * Возвращает массив информации о конкретной вариации
     *
     * @param int $variationId ID выбранной вариации
     *
     * @return array{display_price?: string, display_regular_price?: string, selected_variation?: array<string, mixed>}
     */
    protected function getVariationSelected(int $variationId): array
    {
        $product = wc_get_product($variationId);
        if (!$product instanceof WC_Product_Variation) {
            return [];
        }
        $parentId = $product->get_parent_id();
        $parentAttributes = get_post_meta($parentId, '_product_attributes', true);
        $selected = [];
        foreach ($product->get_attributes() as $attributeSlug => $value) {
            $slug = str_replace('attribute_', '', $attributeSlug);
            if (stripos($slug, 'pa_') !== false) {
                $taxonomy = get_taxonomy($slug);
                $label = $taxonomy && isset($taxonomy->labels->singular_name) ? $taxonomy->labels->singular_name : $slug;
            } else {
                $label = is_array($parentAttributes) && isset($parentAttributes[$slug]['name'])
                    ? $parentAttributes[$slug]['name']
                    : $slug;
            }
            $selected[$label] = $value;
        }
        return [
            'display_price'        => $product->get_price(),
            'display_regular_price' => $product->get_regular_price(),
            'selected_variation'   => $selected,
        ];
    }

    /**
     * Скрипт сбора данных с карточки товара для WooCommerce TM Extra Product Options
     *
     * @return void
     */
    protected function printTmExtraProductOptionsJs(): void
    {
        ?>
        <script>
          jQuery(document).on('mouseenter', '#buyoneclick_form_order .buyButtonOkForm', function (e) {
            var tmBlock = jQuery('#tm-extra-product-options .tm-extra-product-options-container .tmcp-field-wrap');
            var prevButton = jQuery(this).prev('input');
            /*Свойства*/
            jQuery(tmBlock).each(function (i, e) {
              var name, quant, price;
              if (jQuery(e).hasClass('tc-active')) {
                name = jQuery(e).find('.tm-label').text();
                price = jQuery(e).find('.tc-price .amount').text();
                quant = jQuery(e).find('.tm-quantity input').val();
                jQuery(prevButton).after('<input class="buy-variation-set" name="variation_' + name + '" value="' + quant + '" type="hidden">');
              }
            });
            /*Цена*/
            var prodOptions = jQuery('.tm-custom-price-totals');
            var finalPrice = jQuery(prodOptions).find('.tm-final-totals').text();
            if (finalPrice) {
              jQuery(prevButton).after('<input class="buy-variation-set" name="variation_ЦенаTM" value="' + finalPrice + '" type="hidden">');
            }
          });
        </script>
        <?php
    }

    /**
     * Основной скрипт выбора вариации на клиенте
     *
     * @return void
     */
    protected function printProductVariationJs(): void
    {
        ?>
        <script>
          jQuery(document).ready(function () {
            var selected_variation_id = '';
            jQuery(document).on('mouseenter', '#buyoneclick_form_order .buyButtonOkForm', function (e) {
              jQuery('input.buy-variation-set').remove();
              var variaction_form = jQuery('.variations_form').serialize();
              selected_variation_id = jQuery('input.variation_id').val();
              jQuery('select').blur(function () {
                selected_variation_id = jQuery('input.variation_id').val();
              });
              if (typeof selected_variation_id !== 'undefined') {
                if (selected_variation_id.length < 1) {
                  alert('<?php echo esc_js(__('Please select a variation', 'buy-one-click-woocommerce')); ?>');
                  return false;
                } else {
                  jQuery(this).after('<input class="buy-variation-set" name="variation_id" value="' + selected_variation_id + '" type="hidden">');
                }
                if (typeof variaction_form !== 'undefined') {
                  jQuery(this).after('<input class="buy-variation-set" name="variation_form" value="' + variaction_form + '" type="hidden">');
                }
              }
            });
            jQuery(document).on('mouseenter', '.summary.entry-summary .clickBuyButton', function (e) {
              var variaction_form = jQuery('.variations_form').serialize();
              selected_variation_id = jQuery('input.variation_id').val();
              jQuery('select').blur(function () {
                selected_variation_id = jQuery('input.variation_id').val();
              });
              if (typeof selected_variation_id === 'undefined') {
                return;
              }
              jQuery(this).attr('data-variation_id', selected_variation_id);
            });
          });
        </script>
        <?php
    }

    /**
     * Определяет текущий ID товара
     *
     * @return int
     */
    protected function resolveProductId(): int
    {
        $postId = (int) get_the_ID();
        if ($postId > 0) {
            return $postId;
        }
        global $product;
        if ($product instanceof WC_Product) {
            return $product->get_id();
        }
        global $post;
        if ($post instanceof WP_Post) {
            return (int) $post->ID;
        }
        $scheme = is_ssl() ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        return (int) url_to_postid(rtrim($scheme . $host . $uri, '?'));
    }

    /**
     * Названия активных плагинов
     *
     * @return array<int, string>
     */
    protected function getActivePluginsTitles(): array
    {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        $active = (array) get_option('active_plugins', []);
        $installed = get_plugins();
        $titles = [];
        foreach ($active as $plugin) {
            if (isset($installed[$plugin]['Title'])) {
                $titles[] = $installed[$plugin]['Title'];
            }
        }
        return $titles;
    }

    /**
     * Человекочитаемое значение атрибута вместо его slug
     *
     * @param string $variationValue
     * @param string $variationSlug
     *
     * @return string
     */
    protected function getAttributeValueName(string $variationValue, string $variationSlug = ''): string
    {
        $terms = get_terms([
            'slug'   => $variationValue,
            'number' => 1,
        ]);
        if (is_array($terms) && isset($terms[0]) && $terms[0] instanceof WP_Term) {
            if ($variationSlug !== '') {
                if (stripos($variationSlug, $terms[0]->taxonomy) !== false) {
                    return $terms[0]->name;
                }
            } else {
                return $terms[0]->name;
            }
        }
        return $variationValue;
    }
}
