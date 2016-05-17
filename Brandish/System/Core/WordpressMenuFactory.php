<?php namespace Brandish\System\Core;

class WordpressMenuFactory {
    public static $router;

    public static function getRouter() {
    	if(self::$router instanceof WordpressMenuRouter) return self::$router;
 	     else {
	     	self::$router = new WordpressMenuRouter;
	     	return self::$router;
             }
    }

    public static function create(WordpressAdminMenuBuilder $item) {
        $menuClass = $item->isSubMenu() ? "Brandish\System\Core\WordpressAdminSubMenu" : "Brandish\System\Core\WordpressAdminMenu"; 
        return new $menuClass($item, self::getRouter());
    }
}
