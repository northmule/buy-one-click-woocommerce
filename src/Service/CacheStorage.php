<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Service;

/**
 * На основе объектного кеша, для работы требуются дополнительные
 * плагины работающий с объектным кешем (пример: W3 Total Cache)
 *
 * Class CacheStorage
 */
class CacheStorage
{
    /**
     * @var string
     */
    protected const GROUP_KEY = 'buy_coderun_storage';

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
        wp_cache_set($key, $value, self::GROUP_KEY);
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
        $value = wp_cache_get($key, self::GROUP_KEY);
        if ($value === false) {
            return $default;
        }
        return $value;
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
        wp_cache_delete($key, self::GROUP_KEY);
    }
}
