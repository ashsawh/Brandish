<?php 
use Brandish\Services as Services;
use Brandish\Services\Contracts as Contracts;
use Brandish\Services\Adapters as Adapters;

define("DS", DIRECTORY_SEPARATOR);
define("APP_PATH", __DIR__ . DS . "App" . DS);
define("SYSTEM_PATH", __DIR__ . DS . "System" . DS);
define("CORE_PATH", SYSTEM_PATH . "Core" . DS);
define("VIEW_PATH", APP_PATH . "Views" . DS);
define("CONTROLLER_PATH", APP_PATH . "Controllers" . DS);
define("MODEL_PATH", APP_PATH . "Models" . DS);
define("ASSETS_PATH", __DIR__ . DS . 'Assets' . DS);
define("TEST_PATH", __DIR__ . DS . 'test' . DS);
define("ROOT_PATH", __DIR__ . DS);
define("SERVICES_PATH", ROOT_PATH . 'Services' . DS);

class App {
    private $serviceAliases = [
        'Validation' => 'Brandish\Services\Facades\Validation'
    ];
    
    function __construct($config, $dependencies = '') {
        $this->parseConfig($config);
        $this->addDependencies($dependencies);
        $this->boot();
        $this->autoload();
        $this->assignAlias();
    }
    
    private function autoload() {
        $paths = [CORE_PATH, SYSTEM_PATH, APP_PATH, ROOT_PATH, SERVICES_PATH];
        set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $paths));
        ($currExt = spl_autoload_extensions()) && $currExt .= ',';
        spl_autoload_extensions($currExt . ".class.php"); 

        spl_autoload_register();
        spl_autoload_register(
            function ($className) {
                $file = str_replace('\\', '/', $className);
                ($file = explode('/', $file)) && array_shift($file) && ($file = implode('/', $file));	
                $file .= ".php"; var_dump($file);
                if(is_readable(__DIR__ . DS . $file)) require_once $file;
            }
        );
    }
    
    private function assignAlias() {
        foreach($this->serviceAliases as $alias => $facade) 
            Services\Provider::register($alias, $facade);
    }
    
    private function parseConfig() {
        $config = __FILE__ . DS . 'config.ini';
        if(is_readable($config)) 
            return $this->config = parse_ini_file($config);
    }
    
    private function addDependencies() {
        $dependencies = include(__FILE__ . DS . 'Dependencies.php');
        if(!empty($dependencies))
            if(!empty($this->config['services_adapters']['di'])) {
                $DIAdapter = $this->config['services_adapters']['di'];
                $this->DIAdapter = new $DIAdapter;
                unset($this->config['services_adapters']['di']);
            } else $this->DIAdapter = new Adapters\PHPDIAdapte;
    }
    
    private function boot() {
        foreach($this->config['boot'] as $name => $service) {
            $this->$name = empty($this->DIAdapter) ? 
                    $this->DIAdapter->get($service) : new $service; 
        }
    }
}