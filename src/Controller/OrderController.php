<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Controller;

use BuySMSC;
use Coderun\BuyOneClick\BuyFunction;
use Coderun\BuyOneClick\BuyHookPlugin;
use Coderun\BuyOneClick\Common\Logger;
use Coderun\BuyOneClick\Constant\Options\ActionsForm;
use Coderun\BuyOneClick\Core;
use Coderun\BuyOneClick\Exceptions\DependenciesException;
use Coderun\BuyOneClick\Exceptions\LimitOnSendingFormsException;
use Coderun\BuyOneClick\Exceptions\RequestException;
use Coderun\BuyOneClick\Exceptions\RequireFieldException;
use Coderun\BuyOneClick\Help;
use Coderun\BuyOneClick\Hydrator\CommonHydrator;
use Coderun\BuyOneClick\LoadFile;
use Coderun\BuyOneClick\Repository\Order;
use Coderun\BuyOneClick\ReCaptcha;
use Coderun\BuyOneClick\Response\ErrorResponse;
use Coderun\BuyOneClick\Response\OrderResponse;
use Coderun\BuyOneClick\Response\ValueObject\Product;
use Coderun\BuyOneClick\Utils\Email as EmailUtils;
use Coderun\BuyOneClick\ValueObject\OrderForm;
use WC_Order;
use WC_Session_Handler;

use function get_current_user_id;
use function wc_get_order;
use function wp_json_encode;

/**
 * Class OrderController
 *
 * @package Coderun\BuyOneClick\Controller
 */
class OrderController extends Controller
{
    /**
     * @inheritDoc
     *
     * @return void
     */
    public function init(): void
    {
        add_action(
            sprintf('wp_ajax_%s_buybuttonform', self::REQUEST_KEY),
            [$this, 'sendingOrderFromFormAction']
        );
        add_action(
            sprintf('wp_ajax_nopriv_%s_buybuttonform', self::REQUEST_KEY),
            [$this, 'sendingOrderFromFormAction']
        );
    }

    /**
     * Функция выполняется после нажатия на кнопку в форме заказа
     */
    public function sendingOrderFromFormAction()
    {
        try {
            if (empty($_POST)) {
                throw RequestException::emptyRequest();
            }
            if (!wp_verify_nonce($_POST['_coderun_nonce'], 'one_click_send')) {
                throw RequestException::nonceError();
            }

            $help = Help::getInstance();
            $commonOptions = Core::getInstance()->getCommonOptions();
            $notificationOptions = Core::getInstance()->getNotificationOptions();
            if ($commonOptions->isRecaptchaEnabled()) {
                $check_recaptcha = ReCaptcha::getInstance()->check($commonOptions->getCaptchaProvider());
                if ($check_recaptcha['check'] !== true) {
                    throw DependenciesException::captchaVerificationPluginError($check_recaptcha['message'] ?? '');
                }
            }

            $orderForm = new OrderForm(
                $_POST,
                $notificationOptions,
                $help->module_variation
            );
            $this->checkRequireField($orderForm);
            $this->checkLimitSendForm($orderForm->getProductId());

            $smsGateway = new BuySMSC();
            $smsLog = [];
            if ($notificationOptions->isEnableSendingSmsToClient()) {
                $smsLog = $smsGateway->send_sms(
                    $orderForm->getUserPhone(),
                    BuyFunction::composeSms($notificationOptions->getSmsClientTemplate(), $orderForm)
                );
            }
            //Отправка СМС продавцу
            if ($notificationOptions->isEnableSendingSmsToSeller()) {
                $smsLog = $smsGateway->send_sms(
                    $notificationOptions->getSellerPhoneNumber(),
                    BuyFunction::composeSms($notificationOptions->getSmsSellerTemplate(), $orderForm)
                );
            }

            if ($commonOptions->isEnableFieldWithFiles()) {
                if (!empty(LoadFile::getInstance()->getErrors())) {
                    $this->logger->error(__('File upload error', 'coderun-oneclickwoo'), LoadFile::getInstance()->getErrors());
                }
            }

            if (!$commonOptions->isAddAnOrderToWooCommerce()
                && $orderForm->getUserEmail() && $notificationOptions->isEnableOrderInformation()) {
                EmailUtils::sendAnEmail(
                    $orderForm->getUserEmail(),
                    $orderForm
                );
            }
            if ($notificationOptions->getEmailBcc() != '') {
                EmailUtils::sendAnEmail(
                    $notificationOptions->getEmailBcc(),
                    $orderForm
                );
            }

            $wooOrderId = 0;
            //В таблицу Woo
            if ($commonOptions->isAddAnOrderToWooCommerce() and $orderForm->getCustom() == 0) {
                $wooOrderId = Order::getInstance()->set_order(
                    [
                        'first_name' => $orderForm->getUserName(),
                        'last_name' => '',
                        'company' => '',
                        'email' => $orderForm->getUserEmail(),
                        'phone' => $orderForm->getUserPhone(),
                        'address_1' => $orderForm->getOrderComment(),
                        'address_2' => '',
                        'city' => '',
                        'state' => '',
                        'postcode' => '',
                        'country' => '',
                        'order_status' => 'processing', //Статус заказа который будет установлен
                        'message_notes_order' => __('Quick order form', 'coderun-oneclickwoo'), //Сообщение в заказе
                        'qty' => $orderForm->getQuantityProduct() ?: 1,
                        'product_id' => $orderForm->getProductId(), //ИД товара Woo
                    ]
                );
            }

            $order_field = [
                'product_id' => $orderForm->getProductId(),
                'product_name' => $orderForm->getProductName(),
                'product_meta' => null,
                'product_price' => $orderForm->getProductPrice(),
                'product_quantity'=> $orderForm->getQuantityProduct(),
                'form' => wp_json_encode((new CommonHydrator())->extractToArray($orderForm)),
                'sms_log' => wp_json_encode($smsLog),
                'woo_order_id' => $wooOrderId,
                'user_id' => get_current_user_id(),
            ];

            Order::getInstance()->save_order(
                $order_field
            );
            $orderResponse = new OrderResponse();
            $orderResponse->setMessage(__('The order has been sent', 'coderun-oneclickwoo'));
            $orderResponse->setResult($commonOptions->getSubmittingFormMessageSuccess());
            $orderResponse->setProducts([new Product($orderForm)]);
            $orderResponse->setOrderUuid($orderForm->getOrderUuid());
            if ($wooOrderId) {
                $wcOrder = wc_get_order($wooOrderId);
                if ($wcOrder instanceof WC_Order) {
                    $wcOrder->update_status('processing', 'Quick order form');
                    if ($commonOptions->getActionAfterSubmittingForm() == ActionsForm::SEND_TO_ORDER_PAGE) {
                        $orderResponse->setRedirectUrl($wcOrder->get_checkout_order_received_url());
                    } elseif ($commonOptions->getActionAfterSubmittingForm() == ActionsForm::SEND_TO_ORDER_PAYMENT_PAGE) {
                        $wcOrder->update_status('wc-pending');
                        $orderResponse->setRedirectUrl($wcOrder->get_checkout_payment_url());
                    }
                } else {
                    throw DependenciesException::orderCreationErrorWoo();
                }
            }
            BuyHookPlugin::buyClickNewrder(
                (new CommonHydrator())->extractToArray($orderResponse),
                $order_field
            );

            wp_send_json_success(
                (new CommonHydrator())->extractToArray($orderResponse)
            );
        } catch (RequestException $ex) {
            $errorResponse = new ErrorResponse();
            $errorResponse->setMessage(__('request error', 'coderun-oneclickwoo'));
            $this->logger->error($ex->getMessage());
            wp_send_json_error((new CommonHydrator())->extractToArray($errorResponse));
        } catch (DependenciesException|RequireFieldException|LimitOnSendingFormsException $ex) {
            $errorResponse = new ErrorResponse();
            $errorResponse->setMessage($ex->getMessage());
            $this->logger->error($ex->getMessage());
            wp_send_json_error((new CommonHydrator())->extractToArray($errorResponse));
        }
    }

    /**
     * Проверка обязательных полей
     *
     * @param $orderForm OrderForm
     *
     * @return void
     * @throws RequireFieldException
     */
    protected function checkRequireField(OrderForm $orderForm): void
    {
        $commonOptions = Core::getInstance()->getCommonOptions();

        if ($commonOptions->isFieldEmailIsRequired() && !$orderForm->getUserEmail()) {
            throw RequireFieldException::fieldIsRequired('email');
        }
        if ($commonOptions->isFieldNameIsRequired() && !$orderForm->getUserName()) {
            throw RequireFieldException::fieldIsRequired('name');
        }
        if ($commonOptions->isFieldPhoneIsRequired() && !$orderForm->getUserPhone()) {
            throw RequireFieldException::fieldIsRequired('phone');
        }
        if ($commonOptions->isFieldCommentIsRequired() && !$orderForm->getUserComment()) {
            throw RequireFieldException::fieldIsRequired('message');
        }
        if ($commonOptions->isConsentToProcessing() && !$orderForm->isConset()) {
            throw RequireFieldException::fieldIsRequired('consent');
        }
        $files = array_filter($orderForm->getFiles()['name'] ?? []); // todo тут ошибка

        if ($commonOptions->isEnableFieldWithFiles()
            && $commonOptions->isFieldFilesIsRequired()
            && count($files) == 0) {
            throw  RequireFieldException::fieldIsRequired('files');
        }
    }

    /**
     * Ограничение на отправку формы раз в N секунд
     *
     * @param int $product_id ИД товара
     * @throws LimitOnSendingFormsException
     */
    protected function checkLimitSendForm(int $product_id): void
    {
        /** @var WC_Session_Handler $session */
        $session = WC()->session;
        $commonOptions = Core::getInstance()->getCommonOptions();
        $key = sprintf('buy_one_click_woocommerce_%s_%s', $product_id, $session->get_customer_unique_id());
        if (!$session->get($key, false)) {//Установка
            $session->set($key, time());
        } else {
            if (($session->get($key, 0) + $commonOptions->getFormSubmissionLimit()) > time()) {
                throw LimitOnSendingFormsException::error($commonOptions->getFormSubmissionLimitMessage());
            }
        }
    }
}
