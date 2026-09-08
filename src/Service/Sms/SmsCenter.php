<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Service\Sms;

use Coderun\BuyOneClick\Common\Logger;
use Coderun\BuyOneClick\Options\Notification as NotificationOptions;
use Exception;

/**
 * Ужасный старый код от SMSC.ru
 *
 * Class SmsCenter
 */
class SmsCenter
{
    /**
     * Настройки плагина
     *
     * @var NotificationOptions
     */
    protected NotificationOptions $notificationOptions;

    /**
     * @param NotificationOptions $notificationOptions
     */
    public function __construct(NotificationOptions $notificationOptions)
    {
        $this->notificationOptions = $notificationOptions;
    }


    /**
     * Обычная отправка СМС
     *
     * @return array<string, mixed>
     * @throws Exception
     */
    public function send_sms($phones, $message, $translit = 0, $time = 0, $id = 0, $format = 0, $sender = false, $query = '', $files = [])
    {
        static $formats = [1 => 'flash=1', 'push=1', 'hlr=1', 'bin=1', 'bin=2', 'ping=1', 'mms=1', 'mail=1', 'call=1'];

        $m = $this->_smsc_send_cmd(
            'send',
            'cost=3&phones=' . urlencode($phones) . '&mes=' . urlencode($message) .
            "&translit=$translit&id=$id" . ($format > 0 ? '&' . $formats[$format] : '') .
            ($sender === false ? '' : '&sender=' . urlencode($sender)) .
            ($time ? '&time=' . urlencode($time) : '') . ($query ? "&$query" : ''),
            $files
        );
        if ($this->notificationOptions->isEnableSmsDebug()) {
            if ($m[1] > 0) {
                $m['debud'] = "Сообщение отправлено успешно. ID: $m[0], всего SMS: $m[1], стоимость: $m[2], баланс: $m[3].\n";
            } else {
                $m['debud'] = "Ошибка №- {$m[1]} \n";
            }
        }

        return $m;
    }

    protected function _smsc_send_cmd($cmd, $arg = '', $files = [])
    {
        $url = ($this->notificationOptions->isEnableSmsServiceHttpsProtocol() ? 'https' : 'http')
            . "://smsc.ru/sys/$cmd.php?login=" . urlencode($this->notificationOptions->getSmsServiceLogin())
            . '&psw=' . urlencode($this->notificationOptions->getSmsServicePassword())
            . '&fmt=1&charset=' . $this->notificationOptions->getSmsCharacterEncoding() . '&' . $arg;

        $i = 0;
        do {
            if ($i) {
                sleep(2 + $i);

                if ($i == 2) {
                    $url = str_replace('://smsc.ru/', '://www2.smsc.ru/', $url);
                }
            }

            $ret = $this->_smsc_read_url($url, $files);
        } while ($ret == '' && ++$i < 4);

        if ($ret == '') {
            if ($this->notificationOptions->isEnableSmsDebug()) {
                Logger::getInstance()->error('Ошибка чтения адреса SMS-сервиса.');
            }

            $ret = ','; // фиктивный ответ
        }

        $delim = ',';

        if ($cmd == 'status') {
            $parsedArgs = [];
            parse_str($arg, $parsedArgs);

            if (isset($id) && strpos($id, ',')) {
                $delim = "\n";
            }
        }

        return explode($delim, $ret);
    }

    // Чтение ответа сервиса через HTTP API WordPress (wp_http)

    protected function _smsc_read_url($url, $files)
    {
        $ret = '';
        $post = $this->notificationOptions->isEnableSmsServicePostProtocol() || strlen($url) > 2000 || $files;

        $args = [
            'timeout'     => 60,
            'sslverify'   => false,
            'redirection' => 5,
        ];

        if ($post) {
            $parts = explode('?', $url, 2);
            $baseUrl = reset($parts);
            $queryString = count($parts) > 1 ? $parts[1] : '';

            $queryParams = [];
            if ($queryString !== '') {
                parse_str($queryString, $queryParams);
            }

            $body = $queryParams;
            if ($files) {
                foreach ($files as $i => $path) {
                    if (file_exists($path)) {
                        $body['file' . $i] = $path;
                    }
                }
            }

            $args['method'] = 'POST';
            $args['body'] = $body;
            $url = $baseUrl;
        } else {
            $args['method'] = 'GET';
        }

        $response = wp_remote_request($url, $args);

        if (is_wp_error($response)) {
            if ($this->notificationOptions->isEnableSmsDebug()) {
                Logger::getInstance()->error('Ошибка запроса к сервису СМС: ' . $response->get_error_message());
            }
            return '';
        }

        $statusCode = wp_remote_retrieve_response_code($response);
        if ($statusCode >= 200 && $statusCode < 300) {
            $ret = wp_remote_retrieve_body($response);
        }

        return $ret;
    }
}
