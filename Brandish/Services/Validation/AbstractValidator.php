<?php namespace Brandish\Services\Validation;
use Brandish\Services\Containers as Containers;
use Brandish\Services\Messaging as Messaging;

/*
 * @author ashwin@answersnetwork.com 
 * @abstract  
 * AbstractInputValidator governs the filtering for super globals classes. 
 * Each superglobal has a class associated that inherits this abstract base class.
 * Ex. RequestValidator validates the request superglobal.
 * 
 */

abstract class AbstractValidator implements IValidator {
    /* 
     * Diction instance containing default placeholder error messages.
     * 
     * @var Brandish\Services\Messaging\IDiction 
     */
    protected $diction;
    /* 
     * Cipher instance that translates placeholder messages for user readability.
     * @var Brandish\Services\Messaging\IPlaceholderCipher 
     */
    protected $cipher;
    /* 
     * Message book instance that contains the actual errors that can be used 
     * in views.
     * @var Brandish\Services\Messaging\MessageBook 
     */
    protected $book;
    
    /*
     * Array of field to custom messages for validation use.
     * @var array
     */
    protected $customMessages;
    
     /* 
     * Import field validations 
     * Contains the actual validation and sanitation functions available to the user. 
     */
    use TraitValidations;
    
    /*
     * Constuctor creates instances of the placeholder cipher, and message book
     * as well as accepts the message diction used by this validator.
     * @param IDiction $diction
     * @param array $messages Contains custom error message placeholders 
     * @return void
     */
    function __construct(
	Containers\IContainer $diction,
	Messaging\MessageBook $book, 
	Messaging\PlaceholderCipher $cipher,
	$messages) {  

        $this->diction = $diction;
        is_array($messages) && $this->customMessages = $messages;
	$this->cipher = $cipher;
	$this->book = $book;
    }
    
    /* 
     * Returns the actual data contained in the superglobal.
     * @returns array 
     */
    abstract protected function getInput();
    
    /* 
     * @private
     * Flatten multi-dimensional arrays.
     * @param array $array
     * @return array 
     */
    protected function flattenArray(&$array) {
        $flatArray = [];
        array_walk_recursive($array, function($value) use (&$flatArray) {
            return $flatArray[] = $value;
        });
        return $flatArray;
    }
    
    /*
     * Push specified variables to a closure and return the results.
     * @param Closure $closure
     * @param single|array $vars
     * @param mixed $options
     * @return mixed $results 
     */
    protected function isArrayForLoop(\Closure $closure, $vars, $options = "") {
        if(!is_array($vars)) $results = $closure($vars, $options);
            else foreach($vars as $var) $results[$var] = $closure($var, $options); 
        return $results;
    }
    
    /*
     * Pushes specified variables through closure. Assumes $vars is an 
     * associative array.
     * @param Closure $closure
     * @param single|array $vars
     * @param string $assertion
     * @return bool
     */
    protected function isAssocArrayForLoop(\Closure $closure, $vars, $assertion = "") { 
        if(!is_array($vars)) return $closure($vars, $assertion);
        else {
            foreach($vars as $var => $assertion) if($closure($var, $assertion)) return true;
            return false;  
	}
    }
	
    /*
     * Sanitize subject input by specified regexp value.
     * @param single|array $vars
     * @param string $regex
     * @return mixed
     */
    public function sanitizeByRegex($vars, $regex) {
        try {
            //$this->isValidInput($vars);
            return $this->isArrayForLoop(function($var, &$regex) {
                return preg_replace($regex, '', $this->getInput()[$var]);
                }, $vars, $regex);	
        } catch (InputException $ex) {
            echo $ex->getMessage();
        }
    }
}
