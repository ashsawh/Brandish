<?php namespace Brandish\Services\Adapters;
namespace Brandish\Services\Contracts;

class PHPDIAdapter implements Contacts\IDiAdapter {   
    public function build($dependencies) {
        $builder = new DI\ContainerBuilder($dependencies);
        return $this->DI = $builder->build();
    } 
    
    public function make($name, $parameters) {
        return $this->DI->make($name, $parameters);
    }
    
    public function set($name, $parameters) {
        return $this->DI->set($name, $parameters);
    }
    
    public function get($name) {
        return $this->DI->get($name);
    }
    
    public function has($name) {
        return $this->DI->has($name);
    }
}