<?php namespace Brandish\App\Libraries;
use Brandish\System\Core as Core;

class RubiconHooks extends Core\AbstractWordpressHooks {  
    public function hookMenu() {
        foreach($this->menu as $item) {
            $item->registerMenuHook();
        }
    }
    
    private function createAdTable() { 
        $sql = "CREATE TABLE IF NOT EXISTS rubi_ads (
                    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    style TEXT,
                    name VARCHAR(100), 
                    code TEXT,
                    createDate TIMESTAMP DEFAULT NOW()
                );";
        $this->dao->query($sql);
    }
    
    private function createAdDetailsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS rubi_ads_details (
                    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    adID INT NOT NULL,
                    tag VARCHAR(50),
                    height SMALLINT(4),
                    width SMALLINT(4),
                    label VARCHAR(50), 
                FOREIGN KEY adsIndex(adID) 
                    REFERENCES rubi_ads(id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE
                );";
        $this->dao->query($sql);
    }
    
    private function deleteAdTable() {
        $sql = "DROP TABLE IF EXISTS rubi_ads";
        $this->dao->query($sql);
    }
    
    private function deleteAdDetailsTable() {
        $sql = "DROP TABLE IF EXISTS rubi_ads_details";
        $this->dao->query($sql);
    }
    
    public function hookDeactivate() {
	$this->deleteAdDetailsTable();
        $this->deleteAdTable();
    }
    
    public function hookUninstall() {
    }
    
    public function hookActivate() {
        $this->createAdTable();
        $this->createAdDetailsTable();    
    }
}
