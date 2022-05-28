<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Controller;

interface ControllerInterface
{
    /**
     * Инициализация события, подпись на перехват запроса WordPress
     *
     * @return mixed
     */
    public function init();
}
