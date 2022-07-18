<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Utils;

use Coderun\BuyOneClick\Core;
use Coderun\BuyOneClick\ValueObject\OrderForm;

/**
 * Class Email
 */
class Email
{
    /**
     * Отправка Email
     *
     * @param string    $emailTo
     * @param OrderForm $orderForm
     *
     * @return void
     */
    public static function sendAnEmail(string $emailTo, OrderForm $orderForm): void
    {
        $notificationOptions = Core::getInstance()->getNotificationOptions();
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            sprintf('From: %s <%s>', $orderForm->getCompanyName(), $notificationOptions->getEmailFromWhom()),
        ];
        wp_mail(
            $emailTo,
            $orderForm->getCompanyName(),
            self::buildingEmailTemplate($orderForm),
            implode("\r\n", $headers)
        );
    }

    /**
     * Сборка шаблона
     *
     * @param OrderForm $orderForm
     *
     * @return string
     */
    private static function buildingEmailTemplate(OrderForm $orderForm): string
    {
        $filesMessage = '';
        if ($orderForm->getFilesUrlCollection()) {
            $filesMessage = sprintf('<td style="border-color: #132cba; text-align: center; vertical-align: middle;">%s: </td>', __('Files', 'coderun-oneclickwoo'));
            $filesMessage .= sprintf(
                '<td style="border-color: #132cba; text-align: center; vertical-align: middle;">%s</td>',
                $orderForm->getFilesLink()
            );
        }

        $costOfGoods = $orderForm->getProductPriceWithTax();
        if ($costOfGoods == 0) {
            $costOfGoods = $orderForm->getProductPrice();
        }

        $message = '
<table style="height: 255px; border-color: #1b0dd9;" border="2" width="579">
<tbody>
<tr>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;" colspan="2">' . $orderForm->getCompanyName() . '</td>
</tr>
<tr>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;"> ' . __('Date', 'coderun-oneclickwoo') . ': </td>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;">' . $orderForm->getOrderTime() . '</td>
</tr>
<tr>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;">' . __('Link to the product', 'coderun-oneclickwoo') . ': </td>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;">' . $orderForm->getProductUrl() . '</td>
</tr>
<tr>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;"> ' . __('Price', 'coderun-oneclickwoo') . ': </td>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;">' . $costOfGoods . '</td>
</tr>
<tr>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;">' . __('Name', 'coderun-oneclickwoo') . '</td>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;">' . $orderForm->getProductName() . '<br>' . $orderForm->getVariationData() . '</td>
</tr>
<tr>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;">' . __('Quantity', 'coderun-oneclickwoo') . '</td>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;">' . $orderForm->getQuantityProduct() . '</td>
</tr>
<tr>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;">' . __('Email', 'coderun-oneclickwoo') . '</td>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;">' . $orderForm->getUserEmail() . '</td>
</tr>
<tr>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;">' . __('Phone number', 'coderun-oneclickwoo') . '</td>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;">' . $orderForm->getUserPhone() . '</td>
</tr>
<tr>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;">' . __('Customer', 'coderun-oneclickwoo') . '</td>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;">' . $orderForm->getUserName() . '</td>
</tr>
' . $filesMessage . '
<tr>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;"> ' . __('Additionally', 'coderun-oneclickwoo') . ' </td>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;"> ' . $orderForm->getUserComment() . ' </td>
</tr>
<tr>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;" colspan="2">' . $orderForm->getOrderAdminComment() . '</td>
</tr>
</tbody>
</table>
&nbsp;
        ';
        return $message;
    }
}
