<?php namespace Brandish\System\Core;

interface IRouter {
    public function route($controller);
    public function getControllerPath();
}
