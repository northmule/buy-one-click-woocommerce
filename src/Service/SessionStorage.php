<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Service;

/**
 * Class SessionStorage
 */
class SessionStorage
{
    /**
     * @var string
     */
    protected const SESSION_KEY = 'buy_session_storage';

    /**
     * Установка ключа и значения
     *
     * @param string $key
     * @param $value
     *
     * @return void
     */
    public function setSessionValue(string $key, $value): void
    {
        wp_cache_set($key, $value, self::SESSION_KEY);
    }

    /**
     * Получить значение
     *
     * @param string  $key
     * @param $default
     *
     * @return mixed|null
     */
    public function getSessionValue(string $key, $default = null)
    {
        wp_cache_get($key, self::SESSION_KEY);
    }

    /**
     * Удалить ключ
     *
     * @param string $key
     *
     * @return void
     */
    public function deleteSessionKey(string $key): void
    {
        wp_cache_delete($key, self::SESSION_KEY);
    }
}
