<?php namespace Brandish\System\Core;

interface IWordpressMenu {
    public function registerMenuHook();
    public function getCallBackForController();
}
