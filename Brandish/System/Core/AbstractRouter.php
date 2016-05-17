<?php namespace Brandish\System\Core;

abstract class AbstractRouter implements IRouter {
    public function getControllerPath() {
        return CONTROLLER_PATH;
    }
}
