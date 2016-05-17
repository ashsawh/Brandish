<?php namespace Brandish\App\Libraries;
use Brandish\System\Core as Core;

class RubiconSetupFacade {
    function __construct($originPath) {
        $menuFacade = new RubiconSetupMenuFacade();
        $hooks = new RubiconHooks(
	        	Core\RegistrySingleton::getInstance()->DAO, 
			$originPath,
                        $menuFacade->getMenu()
		 ); 
        $hooks->activate();
        $hooks->deactivate();
        $hooks->uninstall();
        $hooks->menu();
    }
} 
