<?php namespace Brandish\System\Core;

class WordpressRouter extends AbstractRouter {
    public function route($controllerName) {
        if(is_readable($this->getControllerPath() . $controllerName)) {
            require_once $this->getControllerPath() . $controllerName;
            $controller = new $controllerName;
            $controller->compile();
        }
    }
    
    public function parse() {
        $uriArray = explode('/', $this->parse());
        if(current($uriArray) === 'wp-admin') {
            array_shift($uriArray);
            $uri = implode('/', $uriArray);
        } else $uri = implode('/', $uriArray);
        return $uri;
    }
    
    public function getPage() {
        if(preg_match('@(.*)(?=\?|$)@', $this->parse(), $page)) {
           return $page[1]; 
        } else return false;
    }
    
    public function getFunction() {
        if(preg_match('@(\?.*)@', $this->wordpressURI, $commands)) {
            return $commands[1];
        } else return false;
    }
}
