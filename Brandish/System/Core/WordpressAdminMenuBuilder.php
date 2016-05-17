<?php namespace Brandish\System\Core;

class WordpressAdminMenuBuilder {
    public $displayName;
    public $pageTitle;
    public $capability;
    public $slug; 
    public $position;
    public $icon;
    public $parentSlug;
    public $controller;

    public static $referenceRoles = [
            "SUPER ADMIN", "ADMINISTRATOR", "EDITOR", 
            "AUTHOR", "CONTRIBUTER", "SUBSCRIBER"
        ];
    
        
    function __construct($displayName, $pageTitle, $capability, $slug) {
        $this->setDisplayName($displayName);
        $this->setPageTitle($pageTitle);
        $this->setCapability($capability);
        $this->setSlug($slug); 
    }
    

    public function setDisplayName($name) {
        $this->displayName = $name;
    }
    
    public function setPageTitle($title) {
        $this->pageTitle = $title;
    }
    
    public function setSlug($slug) {
        $this->slug = $slug;
    }
    
    public function setCapability($capability) {
        try { 
	    $role = strtoupper($capability);
            if(!in_array($role, self::$referenceRoles))
                throw new WordpressAdminMenuException("Invalid user role chosen.");
            $this->capability = $capability;
        } catch (WordpressAdminMenuException $ex) {
            echo $ex->getMessage();
        }
    }
    
    public function setIcon($icon) {
        $this->icon = $icon;
    }
    
    public function setPosition($position) {
        try {
            if(!is_int($position)) 
                throw new WordpressAdminMenuException("Position must be defined as an integer.");
        } catch (WordpressAdminMenuException $ex) {
            echo $ex->getMessage();
        }
    }
   
    public function setParent(WordpressAdminMenu &$menu) { 
        $this->parentSlug = &$menu->getSlug();
    }
    
    public function isSubMenu() {
        return $this->parentSlug != NULL;
    }
   
    public function setCustomControllerName($controller) {
	$this->controller = $controller;
    }
}
