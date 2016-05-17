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

class DataValidator extends AbstractValidator {
    /*
     * Subject is the data that is being validated
     * @var mixed subject 
     */
    protected $subject;
    
    /*
     * Data validation requires the subject data to be passed through 
     * the constructor 
     * @param mixed $subject
     * @param IDiction $diction
     * @param array $messages
     * @return void
     */
    function __construct(
	$subject, 
	Containers\IContainer $diction, 
	Messaging\MessageBook $book, 
	Messaging\PlaceholderCipher $cipher, 
	$messages) {

        parent::__construct($diction, $book, $cipher, $messages);
        $this->subject = $subject; 
    }
    
    /* 
     * Returns the actual data contained in the superglobal.
     * @return array 
     */
    protected function getInput() {
        return $this->subject;
    }
    
    /*
     * Check if variables exist within data submitted for validation.
     * @param single|array $vars
     * @return bool
     */
    public function exists($vars) {
        $args = $this->flattenArray(func_get_args());   
        $results = array_filter($args, function($var) { 
            return !isset($this->getInput()[$var]); 
        });
	return empty($results); 
    } 
    
    /*
     * Check if a variable contains a specified value
     * @param single|array $vars
     * @param string $value
     * @return bool
     */
    public function contains($var, $value = "") {
	if(is_scalar($var)) {
            $data = $this->getInput()[$var];
            $option = is_array($data) ? FILTER_REQUIRE_ARRAY : FILTER_REQUIRE_SCALAR;
            return filter_var($data, FILTER_UNSAFE_RAW, $option) === $value;
	} elseif(is_array($var)) {
            foreach($var as $key => $value) {
                $data = $this->getInput()[$var];
                $option = is_array($data) ? FILTER_REQUIRE_ARRAY : FILTER_REQUIRE_SCALAR;
                if(filter_var($data, FILTER_UNSAFE_RAW, $option) !== $value) 
                    return false; 
            }
            return true;
	}
    }

    /*
     * Check if input variable exists within superglobal.
     * @param single|array $vars
     * @throw InputException If variable doesn't exist within speficied validation subject.
     * @returns empty
     */
    private function isValidInput($vars) {
        $this->isArrayForLoop(function($var) {
            if(!isset($this->getInput()[$var])) 
                throw new InputException("{$var} is not a valid input.");
        }, $vars);
    }
    
    /*
     * Validate specified input by regex and return if it's true or false.
     * @param single|array $vars
     * @param string $regex
     */
    public function validateByRegex($vars, $regex) {
        try {
            //$this->isValidInput($vars); 
            return $this->isArrayForLoop(function($var, &$regex) {  
                return filter_var($this->getInput()[$var], FILTER_VALIDATE_REGEXP, 
                    array(  
                        "options" => array("regexp" => $regex), 
                        "flags" => is_array($this->getInput()[$var]) 
                            ? FILTER_REQUIRE_ARRAY : FILTER_REQUIRE_SCALAR)
                    );
            }, $vars, $regex);
        } catch (InputException $ex) {
            echo $ex->getMessage();
        }
    }
    
    /*
     * Filter subject input by specified filter and possible options.
     * @param single|array $vars
     * @param int $filter
     * @param mixed $options
     * @returns mixed False if validation fails or in some cases the contents 
     * of the variable on success
     */
    public function filter($vars, $filter, $options = "") {
	try { 
            $options['filter'] = $filter;
            return $this->isArrayForLoop(function($var, $options) {
                return filter_var($this->getInput()[$var], $options['filter'], $options);
                }, $vars, $options);		
	} catch (InputException $ex) {
            echo $ex->getMessage();
        }
    }
	
    /*
     * Filter subject input by a list of validations and the field they apply to.
     * @param array $definitions
     * @returns empty
     */
    public function filterByArray($definitions) {
        try { 
            if(!is_array($definitions)) 
                throw new InputException('Definitions must be defined in an associative array.');		
            return filter_var_array($this->getInput(), $definitions);
        } catch (InputException $ex) {
            echo $ex->getMessage();
        }
    }
}
