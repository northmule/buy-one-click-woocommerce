<?php

namespace Coderun\BuyOneClick;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;


class Logger
{
    protected $path = '';
    
    private static $_instance = null;
    
    /**
     * @var MonologLogger
     */
    protected $log;
    
    protected $name = 'buy-one-click-woocommerce';
    
    protected $enable = true;
    
    /**
     * @var array Дополнительные данные
     */
    protected $context = [];
    
    /**
     * Singletone
     * @return self
     */
    public static function getInstance() {
        
        if (\is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * Logger constructor.
     *
     */
    public function __construct()
    {
        $this->context = [
            'request' => $_REQUEST,
        ];

        $wpUploads = \wp_upload_dir();
        $baseDir = $wpUploads['basedir'];
        $this->path = sprintf('%s/buy-one-click-woocommerce/%s/%s.log', $baseDir, date('d-m-Y'), Service::getInstance()->getUniqueStringToday());
        $this->log = new MonologLogger($this->name);
        $this->log->pushHandler(new StreamHandler($this->path, MonologLogger::INFO));
    }
    
    public function setInfo($message, array $context = [])
    {
        $context = array_merge($context, $this->context);
        if ($this->enable) {
            $this->log->info($message, $context);
        }
    }
    
    public function setError($message, array $context = [])
    {
        $this->log->error($message, $context);
    }
    
    /**
     * Set name
     *
     * @param string $name
     *
     * @return Logger
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * Включить/выключить логирование
     *
     * @param bool $enable
     *
     * @return Logger
     */
    public function setEnable(bool $enable)
    {
        $this->enable = $enable;
        return $this;
    }
    
    
}