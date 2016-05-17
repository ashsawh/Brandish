<?php namespace Brandish\System\Core;

class WordpressAdminSubMenu extends AbstractWordpressAdminMenu {
    function __construct(WordpressAdminMenuBuilder $builder, WordpressMenuRouter $router) {
        parent::__construct($builder, $router);
        if($builder->parentSlug != NULL) $this->parentSlug = $builder->parentSlug; 
    }
    public function registerMenuHook() {
    	add_submenu_page(
    	    $this->parentSlug,
            $this->pageTitle,
            $this->displayName, 
            $this->capability, 
            $this->slug, 
            array($this, getCallBackForController)
        );
    }
}
