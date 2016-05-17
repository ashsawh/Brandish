<?php namespace Brandish\System\Core; 

abstract class AbstractWordpressHooks implements IWordpressHooks {
    protected $originPath;
    protected $dao;
    protected $router;
    protected $menu;

    function __construct($dao, $originPath, $menu) {
	$this->dao = $dao;
	$this->originPath = $originPath;
	$this->menu = $menu;
    }

    public function activate() {
        register_activation_hook($this->originPath, array($this, 'hookActivate'));
    }
    
    public function uninstall() {
        register_uninstall_hook($this->originPath, array($this, 'hookUninstall'));   
    }
    
    public function deactivate() {
        register_deactivation_hook($this->originPath, array($this, 'hookDeactivate'));  
    }
    
    public function menu() {
        add_action('admin_menu', array($this, 'hookMenu'));
    }
}
