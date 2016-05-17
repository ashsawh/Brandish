<?php namespace Brandish\System\Core;
 
interface IWordpressHooks { 
    public function activate();
    public function deactivate();
    public function uninstall();
    public function menu();
    public function hookMenu();
    public function hookActivate();
    public function hookDeactivate();
    public function hookUninstall();
}
