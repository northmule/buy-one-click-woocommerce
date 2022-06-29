<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Controller;

use BuySMSC;
use Coderun\BuyOneClick\BuyHookPlugin;
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
use Coderun\BuyOneClick\Service\SessionStorage;
use Coderun\BuyOneClick\Utils\Email as EmailUtils;
use Coderun\BuyOneClick\Utils\Sms as SmsUtils;
use Coderun\BuyOneClick\ValueObject\OrderForm;
use WC_Order;

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
     *
     * @return void
     * @throws \WC_Data_Exception
     */
    public function sendingOrderFromFormAction(): void
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
                    SmsUtils::composeSms($notificationOptions->getSmsClientTemplate(), $orderForm)
                );
            }
            //Отправка СМС продавцу
            if ($notificationOptions->isEnableSendingSmsToSeller()) {
                $smsLog = $smsGateway->send_sms(
                    $notificationOptions->getSellerPhoneNumber(),
                    SmsUtils::composeSms($notificationOptions->getSmsSellerTemplate(), $orderForm)
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
            $orderResponse->setOrderId(intval($wooOrderId));
            
            if ($wooOrderId) {
                $wcOrder = wc_get_order($wooOrderId);
                if ($wcOrder instanceof WC_Order) {
                    $orderResponse->setOrderNumber($this->getOrderNumber($wcOrder));
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
        $commonOptions = Core::getInstance()->getCommonOptions();
        $uniqueId = $this->getCustomerUniqueId();
        if (empty($uniqueId) || $commonOptions->getFormSubmissionLimit() == 0) {
            return;
        }
        $storage = new SessionStorage();
        $key = sprintf('buy_one_%s_%s', $product_id, $uniqueId);
        if ($storage->getSessionValue($key) == null) {//Установка
            $storage->setSessionValue($key, (time() + $commonOptions->getFormSubmissionLimit()));
        } else {
            if ($storage->getSessionValue($key, 0) > time()) {
                throw LimitOnSendingFormsException::error($commonOptions->getFormSubmissionLimitMessage());
            } else {
                $storage->deleteSessionKey($key);
            }
        }
        return;
    }
    
    /**
     * Номер заказа
     * Номер заказа возможен в совместимых плагинах, таких как custom-order-numbers-for-woocommerce
     *
     * @param WC_Order $order
     *
     * @return string
     */
    protected function getOrderNumber(WC_Order $order):string
    {
        if (method_exists($order, 'get_order_number')) {
            return $order->get_order_number(); // plugin: custom-order-numbers-for-woocommerce
        }
        
        return '';
    }

    /**
     * Уникальный ИД текущего пользователя
     *
     * @return string
     */
    protected function getCustomerUniqueId(): string
    {
        $session = serialize(WC()->session);
        preg_match('/(wp_woocommerce_session_[a-zA-Z\d]+)"/i', $session, $matches);
        $uniqueString = $matches[1] ?? '';
        if (strlen($uniqueString) > 0) {
            $uniqueString = md5($uniqueString);
        } else if(is_user_logged_in()) {
            $uniqueString = (string)get_current_user_id();
        }
        return $uniqueString;
    }

}
