<?php

class RubiconSetupFacade {
    function __construct() {
        $hooks = new RubiconHooks;
        $hooks->activate();
        $hooks->deactivate();
        $hooks->uninstall();
        $hooks->menu();
    }
}