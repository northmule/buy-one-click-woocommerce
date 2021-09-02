<?php

namespace Coderun\BuyOneClick;


class Service
{
    
    protected static $_instance = null;
    
    /**
     *
     * @return Service
     */
    public static function getInstance() {
        
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * Шаблон Письма для WooCommerce
     * @param \WC_Order $order
     * @param boolean $sent_to_admin
     * @param type $plain_text
     */
    public function modificationOrderTemplateWooCommerce($order, $sent_to_admin, $plain_text = false)
    {
        
        if (Core::getInstance()->getOption('modificationOrderTemplate', Core::OPTIONS_NOTIFICATIONS, '') !== 'on') {
            return;
        }
        
        
        $pluginOrder = Order::getInstance()->get_wc_order($order->get_id());
        // $metaData = $order->get_meta_data();
        if (empty($pluginOrder)) {
            return;
        }
        $form = [];
        if (!empty($pluginOrder['form'])) {
            $form = \json_decode($pluginOrder['form'], true);
        }
        if (empty($form)) {
            return;
        }
        $htmlItems = '';
        
        $htmlItems .= '<h2>'.__('In one click','coderun-oneclickwoo').'</h2>';
        
        if (!empty($form['user_name'])) {
            $htmlItems .= sprintf('<p>%s: %s</p>', __('Name','coderun-oneclickwoo'), $form['user_name']);
        }
        if (!empty($form['user_phone'])) {
            $htmlItems .= sprintf('<p>%s: %s</p>', __('Phone','coderun-oneclickwoo'), $form['user_phone']);
        }
        if (!empty($form['user_email'])) {
            $htmlItems .= sprintf('<p>%s: %s</p>', __('Email','coderun-oneclickwoo'), $form['user_email']);
        }
        if (!empty($form['product_name'])) {
            $htmlItems .= sprintf('<p>%s: %s</p>', __('Products','coderun-oneclickwoo'), $form['product_name']);
        }
        
        // Ссылки на файлы
        if (!empty(Help::getInstance()->get_options('buynotification')['links_to_files'])) {
            if (!empty($form['files_url']) && \is_array($form['files_url'])) {
                foreach ($form['files_url'] as $url) {
                    if (empty($url)) {
                        continue;
                    }
                    $htmlItems .= sprintf('<p>%s: %s</p>', __('File url','coderun-oneclickwoo'), $url);
                }
            }
        }
        $html = sprintf('<table><tr><td>%s</td></tr></table>', $htmlItems);
        echo $html;
    }
    
    public function getUniqueStringToday()
    {
        $optionsName = 'coderun_unique_string_today';
        $value = \get_option($optionsName, []);
        if (!is_array($value)
            || !isset($value['dateTime'])
            || !isset($value['string'])
            || !$value['dateTime'] instanceof \DateTime) {
            $value = [
                'dateTime' => new \DateTime('now'),
                'string' => $this->getUuidv4(),
            ];
            update_option($optionsName, $value);
            return $value['string'];
        }
        $dateTime = new \DateTime('now');
        $interval = $dateTime->diff($value['dateTime']);
        if (intval($interval->format('%d')) > 0) {
            delete_option($optionsName);
            return $this->getUniqueStringToday();
        }
        return $value['string'];
    }
    
    public function getUuidv4() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}