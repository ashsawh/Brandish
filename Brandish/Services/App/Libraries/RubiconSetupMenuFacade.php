<?php namespace Brandish\App\Libraries;
use Brandish\System\Core as Core;

class RubiconSetupMenuFacade {
    private $menu;
    
    function __construct() {
        $infoBuilder = new Core\WordpressAdminMenuBuilder(
                            "Rubicon", 
                            "Rubicon Documentation", 
                            "administrator",
                            "rubicon"
                        );
        $infoBuilder->setIcon("dashicons-admin-tools"); 
	$infoBuilder->setCustomControllerName("Rubicon");
        $parent = $this->make($infoBuilder); 
        
        $poolBuilder = new Core\WordpressAdminMenuBuilder(
                        "Ad Pool", 
                        "Rubicon Ad Pool", 
                        "administrator", 
                        "ad-pool"
                    ); 
        $poolBuilder->setParent($parent);
	$poolBuilder->setCustomControllerName("AdPool");
        $this->make($poolBuilder);
        
        $managerBuilder = new Core\WordpressAdminMenuBuilder(
                        "Ad Manager", 
                        "Rubicon Ad Manager", 
                        "administrator", 
                        "ad-manager"
                    ); 
        $managerBuilder->setParent($parent);
	$managerBuilder->setCustomControllerName("AdManager");
       	$this->make($managerBuilder);
        
        $actionBuilder = new Core\WordpressAdminMenuBuilder(
                        "Action Log", 
                        "Rubicon Action Log", 
                        "administrator", 
                        "ad-log"
                    ); 
        $actionBuilder->setParent($parent);
	$actionBuilder->setCustomControllerName("ActionLog");
        $this->make($actionBuilder);
    }
    
    private function make(Core\WordpressAdminMenuBuilder $builder) {
        return $this->menu[] = Core\WordpressMenuFactory::create($builder);
    }

    public function getMenu() {
	return $this->menu;
    }
}
