<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Controller;

use BuySMSC;
use Coderun\BuyOneClick\BuyFunction;
use Coderun\BuyOneClick\BuyHookPlugin;
use Coderun\BuyOneClick\Constant\Options\ActionsForm;
use Coderun\BuyOneClick\Core;
use Coderun\BuyOneClick\Exceptions\LimitOnSendingFormsException;
use Coderun\BuyOneClick\Exceptions\RequireFieldException;
use Coderun\BuyOneClick\Help;
use Coderun\BuyOneClick\Hydrator\CommonHydrator;
use Coderun\BuyOneClick\LoadFile;
use Coderun\BuyOneClick\Repository\Order;
use Coderun\BuyOneClick\ReCaptcha;
use Coderun\BuyOneClick\Response\ErrorResponse;
use Coderun\BuyOneClick\Response\OrderResponse;
use Coderun\BuyOneClick\Utils\Email as EmailUtils;
use Coderun\BuyOneClick\ValueObject\OrderForm;

use function get_current_user_id;
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
            [(new OrderController()), 'sendingOrderFromFormAction']
        );
        add_action(
            sprintf('wp_ajax_nopriv_%s_buybuttonform', self::REQUEST_KEY),
            [(new OrderController()), 'sendingOrderFromFormAction']
        );
    }
    
    /**
     * Функция выполняется после нажатия на кнопку в форме заказа
     */
    public function sendingOrderFromFormAction()
    {
        $errorResponse = new ErrorResponse();
        if (empty($_POST)) {
            $errorResponse->setMessage( __('request error', 'coderun-oneclickwoo'));
           
        }
        if (!wp_verify_nonce($_POST['_coderun_nonce'], 'one_click_send')) {
            $errorResponse->setMessage(__('Something went wrong..', 'coderun-oneclickwoo'));
        }

        $help = Help::getInstance();
        $commonOptions = Core::getInstance()->getCommonOptions();
        $notificationOptions = Core::getInstance()->getNotificationOptions();
        if ($commonOptions->isRecaptchaEnabled()) {
            $check_recaptcha = ReCaptcha::getInstance()->check($commonOptions->getCaptchaProvider());
            if ($check_recaptcha['check'] !== true) {
                $errorResponse->setMessage($check_recaptcha['message']);
            }
        }
    
        if ($errorResponse->isError()) {
            wp_send_json_error((new CommonHydrator())->extractToArray($errorResponse), 200);
        }
        
        $orderForm = new OrderForm(
            $_POST,
            $notificationOptions,
            $help->module_variation
        );
        try {
            $this->checkRequireField($orderForm);
            $this->checkLimitSendForm($orderForm->getProductId());
        } catch (RequireFieldException|LimitOnSendingFormsException $ex) {
            $errorResponse->setMessage($ex->getMessage());
        } finally {
            if ($errorResponse->isError()) {
                $this->logger->setInfo($errorResponse->getMessage());
                wp_send_json_error((new CommonHydrator())->extractToArray($errorResponse));
            }
        }
        
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
                BuyFunction::composeSms($notificationOptions->getSmsSellerTemplate(), $orderForm));
        }
        
        if ($commonOptions->isEnableFieldWithFiles()) {
            if (!empty(LoadFile::getInstance()->getErrors())) {
                $this->logger->setInfo(__('File upload error', 'coderun-oneclickwoo'), LoadFile::getInstance()->getErrors());
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
        try {
            $woo_order_id = 0;
            //В таблицу Woo
            if ($commonOptions->isAddAnOrderToWooCommerce() and $orderForm->getCustom() == 0) {
                $woo_order_id = Order::getInstance()->set_order(
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
                'product_quantity'=> $orderForm->getQuantityProduct() ?: 1,
                'form' => wp_json_encode((new CommonHydrator())->extractToArray($orderForm)),
                'sms_log' => wp_json_encode($smsLog),
                'woo_order_id' => $woo_order_id,
                'user_id' => get_current_user_id(),
            ];
            
            Order::getInstance()->save_order(
                $order_field
            );
            $orderResponse = new OrderResponse();
            $orderResponse->setMessage(__('The order has been sent', 'coderun-oneclickwoo'));
            $orderResponse->setResult($commonOptions->getSubmittingFormMessageSuccess());
            if ($woo_order_id) {
                $wcOrder = \wc_get_order($woo_order_id);
                if ($wcOrder instanceof \WC_Order) {
                    $wcOrder->update_status('processing', 'Quick order form');
                    if ($commonOptions->getActionAfterSubmittingForm() == ActionsForm::SEND_TO_ORDER_PAGE) {
                        $orderResponse->setRedirectUrl($wcOrder->get_checkout_order_received_url());
                    } elseif ($commonOptions->getActionAfterSubmittingForm() == ActionsForm::SEND_TO_ORDER_PAYMENT_PAGE) {
                        $wcOrder->update_status('wc-pending');
                        $orderResponse->setRedirectUrl($wcOrder->get_checkout_payment_url());
                    }
                } else {
                    throw new \Exception(__('Couldn\'t create WooCommerce order', 'coderun-oneclickwoo'). ' №'.$woo_order_id);
                }
            }
            BuyHookPlugin::buyClickNewrder(
                (new CommonHydrator())->extractToArray($orderResponse), $order_field
            );
    
            wp_send_json_success(
                (new CommonHydrator())->extractToArray($orderResponse)
            );
        } catch (Exception $ex) {
            $this->logger->setInfo($ex->getMessage());
        }
        
        $errorResponse->setMessage(__('There were errors when placing an order. The order has not been formed.', 'coderun-oneclickwoo'));
        wp_send_json_error((new CommonHydrator())->extractToArray($errorResponse), 200);
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
        $sessionKey = 'BUY_ONE_CLICK_WOOCOMMERCE';
        $key = sprintf('ORDER_LAST_DATE_%s', $product_id);
        if (empty($_SESSION[$sessionKey][$key])) {//Установка
            $_SESSION[$sessionKey][$key] = time();
        } else {
            if (($_SESSION[$sessionKey][$key] + $commonOptions->getFormSubmissionLimit()) > time()) {
                throw LimitOnSendingFormsException::error($commonOptions->getFormSubmissionLimitMessage());
            } else {
                $_SESSION[$sessionKey][$key] = time();
            }
        }
    }
    
}