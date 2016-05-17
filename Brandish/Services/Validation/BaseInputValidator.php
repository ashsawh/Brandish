<?php namespace Brandish\Services\Validation;
use Brandish\Services\Containers as Containers;
use Brandish\Services\Messaging as Messaging;


/*
 * @author ashwin@answersnetwork.com 
 * @abstract  
 * AbstractInputValidator governs the filtering for super globals classes. 
 * Each superglobal has a class associated that inherits this abstract base class.
 * As well, every derived input class is a singleton class.
 * Ex. RequestValidator is a singleton validates the request superglobal.
 * 
 */

abstract class BaseInputValidator extends AbstractInputValidator {
    /*
     * Instance of InputValidator
     * @var object 
     */
    private static $instance;

    /*
     * @return int Corresponding global filter type.
     */
    protected function getInputType() { 
        return static::TYPE; 
    }
	
    /*
     * Create a new instance of this class if one doesn't exist and return it.
     * @param IDiction Diction containing default validation placeholder messages
     * @param array $messages Custom placerholder messages
     * @return $this 
     */
    public static function make(
	Containers\IContainer $diction, 
	Messaging\IMessageBook $book,
	Messaging\IPlaceholderCipher $cipher, 
	$messages) {

        return empty(self::$instance) ? 
            self::$instance = new static($diction, $book, $cipher, $messages) : self::$instance;
    }

}
