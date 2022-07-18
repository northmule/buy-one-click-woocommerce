<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Service;

use Coderun\BuyOneClick\Entity\Order as OrderEntity;
use Coderun\BuyOneClick\Options\Notification as NotificationOptions;
use Coderun\BuyOneClick\Repository\Order as OrderRepository;
use Coderun\BuyOneClick\ValueObject\OrderDataForAdmin;
use Exception;
use WC_Order;

use function json_decode;
use function sprintf;

/**
 * Class EmailTemplate
 *
 * @package Coderun\BuyOneClick\Service
 */
class EmailTemplate
{
    /**
     * Настройки плагина
     *
     * @var NotificationOptions
     */
    protected NotificationOptions $notificationOptions;
    /**
     * @var OrderRepository
     */
    protected OrderRepository $orderRepository;

    /**
     * @param OrderRepository     $orderRepository
     * @param NotificationOptions $notificationOptions
     */
    public function __construct(
        OrderRepository $orderRepository,
        NotificationOptions $notificationOptions
    ) {
        $this->orderRepository = $orderRepository;
        $this->notificationOptions = $notificationOptions;
    }

    /**
     * Шаблон Письма для WooCommerce
     *
     * @param WC_Order $order
     *
     * @return string
     * @throws Exception
     */
    public function modificationOrderTemplateWooCommerce(
        WC_Order $order
    ): string {
        if (!$this->notificationOptions->isEnableOrderInformationToTemplateWoo()) {
            return '';
        }
        $pluginOrder = $this->orderRepository->findOneOrderByOrderWooCommerceId($order->get_id());
        if (!$pluginOrder instanceof OrderEntity) {
            return '';
        }
        $form = null;
        if ($pluginOrder->getForm()) {
            $form = new OrderDataForAdmin(
                json_decode($pluginOrder->getForm(), true)
            );
        }
        if (!$form instanceof OrderDataForAdmin) {
            return '';
        }
        $htmlItems = '<h2>' . __('In one click', 'coderun-oneclickwoo') . '</h2>';
        if ($form->getUserName()) {
            $htmlItems .= sprintf('<p>%s: %s</p>', __('Name', 'coderun-oneclickwoo'), $form->getUserName());
        }
        if ($form->getUserPhone()) {
            $htmlItems .= sprintf('<p>%s: %s</p>', __('Phone', 'coderun-oneclickwoo'), $form->getUserPhone());
        }
        if ($form->getUserEmail()) {
            $htmlItems .= sprintf('<p>%s: %s</p>', __('Email', 'coderun-oneclickwoo'), $form->getUserEmail());
        }
        if ($form->getProductName()) {
            $htmlItems .= sprintf('<p>%s: %s</p>', __('Products', 'coderun-oneclickwoo'), $form->getProductName());
        }

        if ($this->notificationOptions->isEnableFileLinks()) {
            foreach ($form->getFiles() as $url) {
                $htmlItems .= sprintf('<p>%s: %s</p>', __('File url', 'coderun-oneclickwoo'), $url);
            }
        }
        return sprintf('<table><tr><td>%s</td></tr></table>', $htmlItems);
    }
}
