<?php
namespace Coderun\BuyOneClick;

use PHPUnit\Framework\TestCase;

// https://klisl.com/phpunit_basics_1.html
class AjaxTest extends TestCase
{
    static $form = [];
    protected $pluginOptions = [];
    
    public function testCheckRequireField()
    {
    
    }
    
    public static function setUpBeforeClass(): void
    {
        self::$form = [
        
        ];
    }
    
    public function setUp(): void
    {
        $this->pluginOptions = [
            'buyoptions' => [
            
            ],
        ];
    }
    
}