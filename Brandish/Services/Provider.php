<?php namespace Brandish\Services;
use Brandish\Services\Facades as Facades;

class Provider {
    private static $repository;
    
    public static function register($alias, $facade) {
        self::$repository[$alias] = $facade;
        self::alias($alias, $facade);
    }
    
    public static function all() {
        return self::$repository;
    }
    
    private function alias($alias, $facade) { 
        #$facade = str_replace('\\', DIRECTORY_SEPARATOR, $facade);
        return class_alias($facade, $alias);
    }   
}
