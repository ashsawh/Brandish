<?php namespace Brandish\System\Core;
use Brandish\App\Controller as Controller;

class WordpressMenuRouter extends AbstractRouter {
    public function getControllerPath() {
        return CONTROLLER_PATH;
    }

    public function route($controllerFileName) {
        if(is_readable($this->getControllerPath() . $controllerFileName)) { 
            require_once $this->getControllerPath() . $controllerFileName; 
            $controllerQualifiedName = "\Brandish\\App\\Controllers\\" . strtok($controllerFileName, ".");
	    $controller = new $controllerQualifiedName; 
            if($controller) $controller->compile();
        }
    }
}
