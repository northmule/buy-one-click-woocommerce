<?php
namespace Coderun\BuyOneClick;


/**
 * События
 */
class BuyHookPlugin {
    
    /**
     * Плагин загружен
     */
    public static function load() {
        do_action('buy_click_load');
    }

    /**
     * Вызывается после создания нового заказа
     * @param array $arResult результат функции с заказом
     * @param array $arLog лог(журнал плагина)
     */
    public static function buyClickNewrder($arResult, $arLog) {
        do_action('buy_click_new_order', $arResult, $arLog);
    }
    
    /**
     * Вызывается после сохранения в таблицу данных о заказе
     */
    public static function saveOrderToTable($order_id) {
        do_action('buy_click_save_order_to_table',$order_id);
    }

}
