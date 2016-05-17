<?php namespace Brandish\Services\Validation;

/*
 * @author ashwin@answersnetwork.com 
 * @abstract  
 * AbstractInputValidator governs the filtering for super globals classes. 
 * Each superglobal has a class associated that inherits this abstract base class.
 * Ex. RequestValidator validates the request superglobal.
 * 
 */

abstract class AbstractInputValidator extends AbstractValidator {
    
    /* 
     * Import field validations 
     */
    use TraitValidations;
    
    /*
     * Returns the superglobal variable type.
     * @return INT Corresponding integer value for superglobal type. 
     */
    abstract protected function getInputType();
    
    /*
     * Check if variables exist within data submitted for validation.
     * @param single|array $vars
     * @return bool
     */
    public function exists($vars) {
        $args = $this->flattenArray(func_get_args());   
        $results = array_filter($args, function($var) { 
            return !filter_has_var($this->getInputType(), $var); 
        });
	return empty($results); 
    } 
    
    /*
     * Check if a variable contains a specified value
     * @param single|array $vars
     * @param string $value
     * @returns bool
     */
    public function contains($var, $value = "") {
	if(is_scalar($var)) {
            return filter_input($this->getInputType(), $var, FILTER_UNSAFE_RAW) === $value;
	} elseif(is_array($var)) {
            foreach($var as $key => $value) {
            if(filter_input($this->getInputType(), $key, FILTER_UNSAFE_RAW) !== $value) 
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
            if(!filter_has_var($this->getInputType(), $var)) 
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
                return filter_input($this->getInputType(), $var, FILTER_VALIDATE_REGEXP, 
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
                return filter_input($this->getInputType(), $var, $options['filter'], $options);
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
            return filter_input_array($this->getInputType(), $definitions);
        } catch (InputException $ex) {
            echo $ex->getMessage();
        }
    }
}
