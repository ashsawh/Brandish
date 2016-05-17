<?php namespace Brandish\App;
use Brandish\System\Core as Core;
use Brandish\App\Libraries as Lib;

class Loader { 
    private $gateway; 
    private $router;
    private $appVars;
    private $validator;
    private $loader;
    
    function __construct($originPath) { 
        global $wpdb; 
        $registry = Core\RegistrySingleton::getInstance();
        $registry->Name = 'Answers Network Ad Manager';
        $registry->DAO = $wpdb;
	new Lib\RubiconSetupFacade($originPath);
    }
}
