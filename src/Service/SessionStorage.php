<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Service;

/**
 * Class SessionStorage
 */
class SessionStorage
{
    /** @var string  */
    protected const SESSION_KEY = 'buy_session_storage';
    
    /**
     * Установка ключа и значения
     *
     * @param string $key
     * @param        $value
     *
     * @return void
     */
    public function setSessionValue(string $key, $value): void
    {
        $_SESSION[self::SESSION_KEY][$key] = $value;
    }
    
    /**
     * Получить значение
     *
     * @param string $key
     * @param        $default
     *
     * @return mixed|null
     */
    public function getSessionValue(string $key, $default = null)
    {
        return $_SESSION[self::SESSION_KEY][$key] ?? $default;
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
        if (isset($_SESSION[self::SESSION_KEY][$key])) {
            unset($_SESSION[self::SESSION_KEY][$key]);
        }
    }
}