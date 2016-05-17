<?php namespace Brandish\System\Core;

abstract class AbstractWordpressAdminMenu implements IWordpressMenu {
    protected $displayName;
    protected $pageTitle;
    protected $capability;
    protected $slug;
    protected $controller;
    protected $router;
    
    function __construct(WordpressAdminMenuBuilder $builder, WordpressMenuRouter &$router) {
        $this->slug = $builder->slug;
        $this->displayName = $builder->displayName;
        $this->pageTitle = $builder->pageTitle;
        $this->capability = $builder->capability; 
	if($builder->controller != NULL) $this->controller = $builder->controller;
  	$this->router = $router;
    }
    
    abstract function registerMenuHook();
    
    public function getCallBackForController() { 
	$controllerFileName = $this->controller === NULL ? $this->slug . ".php" : $this->controller . ".php" ; 
    	$this->router->route($controllerFileName);
    }
}

