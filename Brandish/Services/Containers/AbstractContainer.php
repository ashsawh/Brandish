<?php namespace Brandish\Services\Containers;

abstract class AbstractContainer implements IContainer {
    function __construct($array, $locale = 'en_US') {
       $this->locale = $locale;	
       $this->collection = $array;
    }

    public function get($key) {  
        return $this->collection[$key];
    }

    public function has($key) {
        return isset($this->collection[$key]);
    }

    public function add($key, $value) {
        $this->collection[$key] = $value;
    }
    
    public function all() {
        return $this->collection;
    }
}
