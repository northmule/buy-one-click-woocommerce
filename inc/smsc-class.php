<?php

if (!defined('ABSPATH')) {
    exit;
}

use Coderun\BuyOneClick\Help;

// SMSC.RU API (smsc.ru) версия 3.4 (05.03.2015)
//Собранно в класс Djo zixn.ru
class BuySMSC {

    /**
     * Обычная отправка СМС
     */
    function send_sms($phones, $message, $translit = 0, $time = 0, $id = 0, $format = 0, $sender = false, $query = "", $files = array()) {

        $options = Help::getInstance()->get_options();

        static $formats = array(1 => "flash=1", "push=1", "hlr=1", "bin=1", "bin=2", "ping=1", "mms=1", "mail=1", "call=1");

        $m = $this->_smsc_send_cmd("send", "cost=3&phones=" . urlencode($phones) . "&mes=" . urlencode($message) .
                "&translit=$translit&id=$id" . ($format > 0 ? "&" . $formats[$format] : "") .
                ($sender === false ? "" : "&sender=" . urlencode($sender)) .
                ($time ? "&time=" . urlencode($time) : "") . ($query ? "&$query" : ""), $files);

        if ($options['buysmscoptions']['debug']) {
            if ($m[1] > 0) {
                $m['debud'] = "Сообщение отправлено успешно. ID: $m[0], всего SMS: $m[1], стоимость: $m[2], баланс: $m[3].\n";
            } else {
                $m['debud'] = "Ошибка №- {$m[1]} \n";
            }
        }

        return $m;
    }

    function _smsc_send_cmd($cmd, $arg = "", $files = array()) {
        $options = Help::getInstance()->get_options();
        $url = ($options['buysmscoptions']['https'] ? "https" : "http") . "://smsc.ru/sys/$cmd.php?login=" . urlencode($options['buysmscoptions']['login']) . "&psw=" . urlencode($options['buysmscoptions']['password']) . "&fmt=1&charset=" . $options['buysmscoptions']['charset'] . "&" . $arg;

        $i = 0;
        do {
            if ($i) {
                sleep(2 + $i);

                if ($i == 2)
                    $url = str_replace('://smsc.ru/', '://www2.smsc.ru/', $url);
            }

            $ret = $this->_smsc_read_url($url, $files);
        } while ($ret == "" && ++$i < 4);

        if ($ret == "") {
            if ($options['buysmscoptions']['debug'])
                echo "Ошибка чтения адреса: $url\n";

            $ret = ","; // фиктивный ответ
        }

        $delim = ",";

        if ($cmd == "status") {
            parse_str($arg);

            if (strpos($id, ","))
                $delim = "\n";
        }

        return explode($delim, $ret);
    }

// Функция чтения URL. Для работы должно быть доступно:
// curl или fsockopen (только http) или включена опция allow_url_fopen для file_get_contents

    function _smsc_read_url($url, $files) {
        $options = Help::getInstance()->get_options();
        $ret = "";
        $post = $options['buysmscoptions']['methodpost'] || strlen($url) > 2000 || $files;

        if (function_exists("curl_init")) {
            static $c = 0; // keepalive

            if (!$c) {
                $c = curl_init();
                curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($c, CURLOPT_TIMEOUT, 60);
                curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
            }

            curl_setopt($c, CURLOPT_POST, $post);

            if ($post) {
                list($url, $post) = explode("?", $url, 2);

                if ($files) {
                    parse_str($post, $m);

                    foreach ($m as $k => $v)
                        $m[$k] = isset($v[0]) && $v[0] == "@" ? sprintf("\0%s", $v) : $v;

                    $post = $m;
                    foreach ($files as $i => $path)
                        if (file_exists($path))
                            $post["file" . $i] = function_exists("curl_file_create") ? curl_file_create($path) : "@" . $path;
                }

                curl_setopt($c, CURLOPT_POSTFIELDS, $post);
            }

            curl_setopt($c, CURLOPT_URL, $url);

            $ret = curl_exec($c);
        } elseif ($files) {
            if ($options['buysmscoptions']['debug'])
                echo "Не установлен модуль curl для передачи файлов\n";
        } else {
            if (!$options['buysmscoptions']['https'] && function_exists("fsockopen")) {
                $m = parse_url($url);

                if (!$fp = fsockopen($m["host"], 80, $errno, $errstr, 10))
                    $fp = fsockopen("212.24.33.196", 80, $errno, $errstr, 10);

                if ($fp) {
                    fwrite($fp, ($post ? "POST $m[path]" : "GET $m[path]?$m[query]") . " HTTP/1.1\r\nHost: smsc.ru\r\nUser-Agent: PHP" . ($post ? "\r\nContent-Type: application/x-www-form-urlencoded\r\nContent-Length: " . strlen($m['query']) : "") . "\r\nConnection: Close\r\n\r\n" . ($post ? $m['query'] : ""));

                    while (!feof($fp))
                        $ret .= fgets($fp, 1024);
                    list(, $ret) = explode("\r\n\r\n", $ret, 2);

                    fclose($fp);
                }
            } else
                $ret = file_get_contents($url);
        }

        return $ret;
    }

}
