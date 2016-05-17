<?php namespace Brandish\System\Core;
 
class RegistrySingleton {
    private static $instance;
	public $registry;
	
    
    private function __construct() {}
    public static function getInstance() {
        return empty(self::$instance) ? self::$instance = new RegistrySingleton() : self::$instance;
    }
    
    public function __set($name, $value) {
        $this->registry[$name] = $value;
    }
    
    public function __get($name) {
        return $this->registry[$name];
    }
}