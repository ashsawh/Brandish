<?php namespace Brandish\System\Core;

class WordpressAdminMenu extends AbstractWordpressAdminMenu {
    private $position;
    private $icon;
    
    function __construct(WordpressAdminMenuBuilder $builder, WordpressMenuRouter $router) {
        parent::__construct($builder, $router);
        if($builder->icon != NULL) $this->icon = $builder->icon;
        if($builder->position != NULL) $this->position = $builder->position;
    }
    
    public function getSlug() {
        return $this->slug;
    }
    
    public function registerMenuHook() {
    	add_menu_page(
    	     $this->pageTitle, 
             $this->displayName, 
             $this->capability, 
             $this->slug, 
             array($this, getCallBackForController),
             $this->icon,
             $this->position
        );
     
    }
}
