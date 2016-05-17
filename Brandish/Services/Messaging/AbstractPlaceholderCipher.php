<?php namespace Brandish\Services\Messaging;

abstract class AbstractPlaceholderCipher implements IPlaceholderCipher {
    protected $message;
    protected $parameters;
    protected $field;
    protected $parameter;

    function __construct() {  
        $this->field = '';
        $this->message = ''; 
        $this->parameters = [];
    }

    protected function setMessage($message) {
        is_scalar($message) && $this->message = $message;
    }

    protected function setParameters($parameters) {
        is_array($parameters) && $this->parameters = $parameters;
    }

    protected function setField($field) {
        $this->field = $field;
    }

    protected function prepareField($field) {
        if(isset($this->fieldNames[$field])) return $this->fieldNames[$field];
        else {
            $field = preg_replace('/[-_]+/', ' ', $field);
            return preg_match('/^:attribute:/', $this->message) ? 
                ucfirst(strtolower($field)) : strtolower($field);
        }
    }

    protected function cipherMax() {
        return str_replace(":max:", max($this->parameters), $this->message);
    }

    protected function cipherMin() {
        return str_replace(":min:", min($this->parameters), $this->message);
    }

    protected function cipherValues() {
        return str_replace(":values:", strtolower(implode(', ', $this->parameters)), $this->message);
    }

    protected function cipherAttribute() {
        $field = $this->prepareField($this->field); 
        return str_replace(':attribute:', $field, $this->message); 
    }

    protected function cipherValue() {
        $value = current($this->parameters);
        next($this->parameters);
        return $value;	
    }
}
