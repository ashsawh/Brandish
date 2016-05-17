<?php namespace Brandish\Services\Containers;

interface IContainer { 
    public function get($key);
    public function has($key);
    public function add($key, $value);
    public function all();
}
