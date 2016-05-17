<?php namespace Brandish\Services\Messaging;

class MessageBook implements IMessageBook {
    protected $format; 
    protected $pages; 
    const DELIMITER = ':';

    function __construct() { 
        #if(is_array($index) && !empty($index)) 
        #	$this->index = array_merge($this->index, $index); 
        $this->format = "<li class='validationItem'>:message:</li>"; 
        $this->pages = []; 
    }

    protected function opener() {?>
    <ul class="validation">
    <?php }

    protected function closer() {?>
    </ul>
    <?php }

    public function page($title, $message) {
        $this->pages[$title][] = $message;	
    }

    public function format($format = '') {
        return $this->format = $format == '' ? $this->format : $format;
    }

    protected function outputMessage($message, $format = '') {  
        $format = $format === '' ? $this->format : $format; ?>
        <?php echo str_replace(':message:', $message, $format); ?>	
    <?php 
    }

    public function get($field, $format = '') { 
        if(isset($this->pages[$field])) {
            $this->opener(); 
            foreach($this->pages[$field] as $message) $this->outputMessage($message, $format); 
            $this->closer();
        } else return false;
    }

    public function all($format = '') {  
        $this->opener();
        foreach($this->pages as $validation => $messages) {
            if(is_array($messages))
                foreach($messages as $message) $this->outputMessage($message, $format);
        }
        $this->closer(); 
    }

    public function has($field) {
        return !empty($this->pages[$field]);
    }

    public function first($field) {
        return reset($this->pages[$field]);	
    }

    public function raw($field = '') {
        return isset($this->pages[$field]) ? $this->pages[$field] : $this->pages;
    }

    public function count($field = '') {
        return $field == '' ? count($this->pages) : count($this->pages[$field]);
    }
}