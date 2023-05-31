<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Controller;

use BuySMSC;
use Coderun\BuyOneClick\Common\ObjectWithConstantState;
use Coderun\BuyOneClick\Constant\Options\ActionsForm;
use Coderun\BuyOneClick\Constant\OrderStatus;
use Coderun\BuyOneClick\Core;
use Coderun\BuyOneClick\Exceptions\DependenciesException;
use Coderun\BuyOneClick\Exceptions\LimitOnSendingFormsException;
use Coderun\BuyOneClick\Exceptions\RequestException;
use Coderun\BuyOneClick\Exceptions\RequireFieldException;
use Coderun\BuyOneClick\Exceptions\UploadingFilesException;
use Coderun\BuyOneClick\Hydrator\CommonHydrator;
use Coderun\BuyOneClick\Repository\Order;
use Coderun\BuyOneClick\ReCaptcha;
use Coderun\BuyOneClick\Response\ErrorResponse;
use Coderun\BuyOneClick\Response\OrderResponse;
use Coderun\BuyOneClick\Response\ValueObject\Product;
use Coderun\BuyOneClick\Service\CacheStorage;
use Coderun\BuyOneClick\Service\Sms\Factory\SmsCenterFactory;
use Coderun\BuyOneClick\Service\UploadingFiles;
use Coderun\BuyOneClick\Utils\Email as EmailUtils;
use Coderun\BuyOneClick\Utils\Hooks;
use Coderun\BuyOneClick\Utils\Sms as SmsUtils;
use Coderun\BuyOneClick\Utils\Translation;
use Coderun\BuyOneClick\ValueObject\FieldNameViaType;
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
            [$this, 'creatingOrder']
        );
        add_action(
            sprintf('wp_ajax_nopriv_%s_buybuttonform', self::REQUEST_KEY),
            [$this, 'creatingOrder']
        );
    }

    /**
     * Функция выполняется после нажатия на кнопку в форме заказа
     *
     * @return void
     * @throws \WC_Data_Exception
     */
    public function creatingOrder(): void
    {
        try {
            if (empty($_POST)) {
                throw RequestException::emptyRequest();
            }
            if (!wp_verify_nonce($_POST['_coderun_nonce'], 'one_click_send')) {
                throw RequestException::nonceError();
            }

            $notificationOptions = $this->notificationOptions;
            if ($this->commonOptions->isRecaptchaEnabled()) {
                $check_recaptcha = ReCaptcha::getInstance()->check($this->commonOptions->getCaptchaProvider());
                if ($check_recaptcha['check'] !== true) {
                    throw DependenciesException::captchaVerificationPluginError($check_recaptcha['message'] ?? '');
                }
            }
            $files = [];
            if ($this->commonOptions->isEnableFieldWithFiles()) {
                $files = (new UploadingFiles())->download(); // прослушивает входящие файлы
            }

            $orderForm = new OrderForm(
                $_POST,
                $notificationOptions,
                $files
            );
            $this->checkRequireField($orderForm, new FieldNameViaType($this->commonOptions));
            $this->checkLimitSendForm($orderForm->getProductId());

            $smsGateway = new SmsCenterFactory();
            $smsLog = [];
            if ($notificationOptions->isEnableSendingSmsToClient()) {
                $smsLog = $smsGateway->create()->send_sms(
                    $orderForm->getUserPhone(),
                    SmsUtils::composeSms($notificationOptions->getSmsClientTemplate(), $orderForm)
                );
            }
            //Отправка СМС продавцу
            if ($notificationOptions->isEnableSendingSmsToSeller()) {
                $smsLog = $smsGateway->create()->send_sms(
                    $notificationOptions->getSellerPhoneNumber(),
                    SmsUtils::composeSms($notificationOptions->getSmsSellerTemplate(), $orderForm)
                );
            }

            if (
                !$this->commonOptions->isAddAnOrderToWooCommerce()
                && $orderForm->getUserEmail() && $notificationOptions->isEnableOrderInformation()
            ) {
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
            if ($this->commonOptions->isAddAnOrderToWooCommerce() and $orderForm->getCustom() == 0) {
                $wooOrderId = Order::getInstance()->set_order(
                    [
                        'first_name'          => $orderForm->getUserName(),
                        'last_name'           => '',
                        'company'             => '',
                        'email'               => $orderForm->getUserEmail(),
                        'phone'               => $orderForm->getUserPhone(),
                        'address_1'           => $orderForm->getOrderComment(),
                        'address_2'           => '',
                        'city'                => '',
                        'state'               => '',
                        'postcode'            => '',
                        'country'             => '',
                        'order_status'        => 'processing', //Статус заказа который будет установлен
                        'message_notes_order' => __('Quick order form', 'coderun-oneclickwoo'), //Сообщение в заказе
                        'qty'                 => $orderForm->getQuantityProduct() ?: 1,
                        'product_id'          => $orderForm->getProductId(), //ИД товара Woo
                    ]
                );
            }

            $order_field = [
                'product_id'       => $orderForm->getProductId(),
                'product_name'     => $orderForm->getProductName(),
                'product_meta'     => null,
                'product_price'    => $orderForm->getProductPrice(),
                'product_quantity' => $orderForm->getQuantityProduct(),
                'form'             => wp_json_encode((new CommonHydrator())->extractToArray($orderForm)),
                'sms_log'          => wp_json_encode($smsLog),
                'woo_order_id'     => $wooOrderId,
                'user_id'          => get_current_user_id(),
            ];

            Order::getInstance()->save_order(
                $order_field
            );
            $orderResponse = new OrderResponse();
            $orderResponse->setMessage(__('The order has been sent', 'coderun-oneclickwoo'));
            $orderResponse->setResult(Translation::translate($this->commonOptions->getSubmittingFormMessageSuccess()));
            $orderResponse->setProducts([new Product($orderForm)]);
            $orderResponse->setOrderUuid($orderForm->getOrderUuid());
            $orderResponse->setOrderId(intval($wooOrderId));

            if ($wooOrderId) {
                $wcOrder = wc_get_order($wooOrderId);
                if ($wcOrder instanceof WC_Order) {
                    $defaultStatus = $this->commonOptions->getWooCommerceOrderStatus() === OrderStatus::WITHOUT_STATUS
                        ? 'processing'
                        : $this->commonOptions->getWooCommerceOrderStatus();
                    $orderResponse->setOrderNumber($this->getOrderNumber($wcOrder));
                    if ($this->commonOptions->getActionAfterSubmittingForm() == ActionsForm::SEND_TO_ORDER_PAGE) {
                        $wcOrder->update_status($defaultStatus, 'Quick order form'); // todo костыль
                        $orderResponse->setRedirectUrl($wcOrder->get_checkout_order_received_url());
                    } elseif ($this->commonOptions->getActionAfterSubmittingForm() == ActionsForm::SEND_TO_ORDER_PAYMENT_PAGE) {
                        $wcOrder->update_status('wc-pending');
                        $orderResponse->setRedirectUrl($wcOrder->get_checkout_payment_url());
                    } else {
                        $wcOrder->update_status($defaultStatus, 'Quick order form'); // todo костыль
                    }
                } else {
                    throw DependenciesException::orderCreationErrorWoo();
                }
            }
            Hooks::buyClickNewrder(
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
        } catch (DependenciesException | RequireFieldException | LimitOnSendingFormsException | UploadingFilesException $ex) {
            $errorResponse = new ErrorResponse();
            $errorResponse->setMessage($ex->getMessage());
            $this->logger->error($ex->getMessage());
            wp_send_json_error((new CommonHydrator())->extractToArray($errorResponse));
        }
    }

    /**
     * Проверка обязательных полей
     *
     * @param $orderForm         OrderForm
     * @param FieldNameViaType            $translatingFields
     *
     * @return void
     */
    protected function checkRequireField(OrderForm $orderForm, FieldNameViaType $translatingFields): void
    {
        if ($this->commonOptions->isFieldEmailIsRequired() && !$orderForm->getUserEmail()) {
            throw RequireFieldException::fieldIsRequired($translatingFields->getUserEmail());
        }
        if ($this->commonOptions->isFieldNameIsRequired() && !$orderForm->getUserName()) {
            throw RequireFieldException::fieldIsRequired($translatingFields->getUserName());
        }
        if ($this->commonOptions->isFieldPhoneIsRequired() && !$orderForm->getUserPhone()) {
            throw RequireFieldException::fieldIsRequired($translatingFields->getUserPhone());
        }
        if ($this->commonOptions->isFieldCommentIsRequired() && !$orderForm->getUserComment()) {
            throw RequireFieldException::fieldIsRequired($translatingFields->getUserComment());
        }
        if ($this->commonOptions->isConsentToProcessing() && !$orderForm->isConset()) {
            throw RequireFieldException::fieldIsRequired($translatingFields->getConsent());
        }
        if (
            $this->commonOptions->isEnableFieldWithFiles()
            && $this->commonOptions->isFieldFilesIsRequired()
            && count($orderForm->getFiles()) == 0
        ) {
            throw  RequireFieldException::fieldIsRequired($translatingFields->getFiles());
        }
    }

    /**
     * Ограничение на отправку формы раз в N секунд
     *
     * @param  int $product_id ИД товара
     * @throws LimitOnSendingFormsException
     */
    protected function checkLimitSendForm(int $product_id): void
    {
        $uniqueId = $this->getCustomerUniqueId();
        if (empty($uniqueId) || $this->commonOptions->getFormSubmissionLimit() == 0) {
            return;
        }
        $storage = new CacheStorage();
        $key = sprintf('buy_one_%s_%s', $product_id, $uniqueId);
        if ($storage->getSessionValue($key) == null) {//Установка
            $storage->setSessionValue($key, (time() + $this->commonOptions->getFormSubmissionLimit()));
        } else {
            if ($storage->getSessionValue($key, 0) > time()) {
                throw LimitOnSendingFormsException::error($this->commonOptions->getFormSubmissionLimitMessage());
            } else {
                $storage->deleteSessionKey($key);
            }
        }
    }

    /**
     * Номер заказа
     * Номер заказа возможен в совместимых плагинах, таких как custom-order-numbers-for-woocommerce
     *
     * @param WC_Order $order
     *
     * @return string
     */
    protected function getOrderNumber(WC_Order $order): string
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
        } elseif (is_user_logged_in()) {
            $uniqueString = (string) get_current_user_id();
        }
        return $uniqueString;
    }
}
