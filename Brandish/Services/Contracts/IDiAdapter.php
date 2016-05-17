<?php namespace Brandish\Services\Contracts; 

interface IDiAdapter {
    public function build($dependencies = []);
    public function make($name, $parameters = []);
    public function set($name, $parameters = []);
    public function get($id);
    public function has($id);
}