<?php namespace Brandish\Services\Messaging;

class PlaceholderCipher extends AbstractPlaceholderCipher {
	public function decipher($message, $field, $parameters) {
		preg_match_all('/:(\w+):/', $message, $replacers);  
		$this->setMessage($message);
		$this->setParameters($parameters);
		$this->setField($field);
		foreach(next($replacers) as $keyword) { 
			$cipher = 'cipher' . ucfirst(strtolower($keyword)); 
			if(method_exists($this, $cipher))
				$this->message = $this->$cipher();
		}
		return $this->message;
	}
}