<?php namespace Brandish\Services\Validation;
use Brandish\Services\Messaging as Messaging;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

trait TraitValidations {
    abstract protected function isArrayForLoop(\Closure $closure, $vars, $options = '');
    abstract protected function flattenArray(&$array);
    abstract protected function getInput();
    abstract protected function isAssocArrayForLoop(\Closure $closure, $vars, $options = '');
    abstract public function exists($vars);
    abstract public function contains($vars, $value);
    abstract public function validateByRegex($vars, $regex);
    abstract public function sanitizeByRegex($vars, $regex);
    abstract public function filter($vars, $filter, $options = '');
    abstract public function filterByArray($definitions);

    /*
     * Assert that variable(s) exists within subject data.
     * @param string|array $vars
     * @return bool|array 
     */
    public function required($vars) {
        $args = $this->flattenArray(func_get_args());
        $results = array_filter($args, function($var) { 
            return $this->getInput()[$var] === '' || $this->getInput()[$var] === NULL; 
        });
        return empty($results); 
    }

    /*
     * Assert that variable(s) contains a min and max number of chars
     * @param string|array $vars
     * @param int $min
     * @param int $max
     * @return bool|array 
     */
    public function characters($vars, $min, $max) {
        $options = ['min' => $min, 'max' => $max];
        return $this->isAssocArrayForLoop(function($var, $options) {
            $varLength = strlen($this->getInput()[$var]); 
            return $varLength >= $options['min'] && $varLength <= $options['max'];
        }, $vars, $options);		
    }

    /*
     * Assert that variable(s) contains less than specified character limit
     * @param string|array $vars
     * @param int $length
     * @return bool|array
     */
    public function shorter($vars, $length = 0) { 
        return $this->isAssocArrayForLoop(function($var, $length) { 
            return strlen($this->getInput()[$var]) > $length;
        }, $vars, $length);
    }
    
    /*
     * Assert that variable(s) contains more than specified character limit
     * @param string|array $vars
     * @param int $length
     * @return bool|array
     */
    public function longer($vars, $length = 0) { 
        return $this->isAssocArrayForLoop(function($var, $length) { 
            return strlen($this->getInput()[$var]) < $length;
        }, $vars, $length);
    }

    /*
     * Assert that variable(s) is exactly specified length
     * @param string|array $vars
     * @param int $length
     * @return bool|array
     */
    public function wide($vars, $length = 0) { 
        return $this->isAssocArrayForLoop(function($var, $length) { 
            return strlen($this->getInput()[$var]) === $length;
        }, $vars, $length);
    }

    /*
     * Assert that numerical variable(s) is less than specified length
     * @param string|array $vars
     * @param int $length
     * @return bool|array
     */
    public function less($vars, $length = 0) { 
        return $this->isAssocArrayForLoop(function($var, $length) { 
            return $this->getInput()[$var] < $length;
        }, $vars, $length);
    }
    
    /*
     * Assert that numerical variable(s) is more than specified length
     * @param string|array $vars
     * @param int $length
     * @return bool|array
     */
    public function more($vars, $length = 0) { 
        return $this->isAssocArrayForLoop(function($var, $length) { 
            return $this->getInput()[$var] > $length;
        }, $vars, $length);
    }

    /*
     * Assert that numerical variable(s) is exactly the same as specified length
     * @param string|array $vars
     * @param int $length
     * @return bool|array
     */
    public function exact($vars, $length = 0) { 
        return $this->isAssocArrayForLoop(function($var, $length) { 
            return $this->getInput()[$var] == $length;
        }, $vars, $length);
    }
    
    /*
     * Assert that numerical variable(s) is between a min and max
     * @param string|array $vars
     * @param int $min
     * @param int $max
     * @return bool|array
     */
    public function between($vars, $min, $max) {
        $options = ['min' => $min, 'max' => $max];
        return $this->isAssocArrayForLoop(function($var, $options) {
            $value = $this->getInput()[$var]; 
            return $value >= $options['min'] && $value <= $options['max'];
        }, $vars, $options);		
    }
    
    /*
     * Assert that variable(s) is only alphabethical characters. 
     * Validated through an RegExp engine is used.
     * @param string|array $vars
     * @return bool|array
     */
    public function alpha($vars) {
        return $this->validateByRegex($vars, '/^[A-Za-z]+$/');	
    }
    
    /*
     * Assert that variable(s) is numeric
     * @param string|array $vars
     * @return bool|array
     */
    public function numeric($vars) {
        return $this->filter($vars, FILTER_VALIDATE_INT);	
    }
    
    /*
     * Assert that variable(s) is numeric
     * @param string|array $vars
     * @return bool|array
     */
    public function alphaNumeric($vars) {
        return $this->validateByRegex($vars, '/^[A-Za-z0-9]+$/');
    }

    /*
     * Assert that variable(s) is a sentence. Sentence is defined as 
     * alpha-numeric characters with punctuations.
     * @param string|array $vars
     * @return bool|array
     */
    public function sentence($vars) {
        return $this->validateByRegex($vars, '/^[A-Za-z\.,-_\?!]+$/');
    }
    
    /*
     * Assert that variable(s) is a boolean value.
     * @param string|array $vars
     * @return bool|array
     */
    public function bool($vars) {
        return $this->filter($vars, FILTER_VALIDATE_BOOLEAN);
    }
    
    /*
     * Assert that variable(s) is a valid email address.
     * @param string|array $vars
     * @return bool|array
     */
    public function email($vars) {
        return $this->filter($vars, FILTER_VALIDATE_EMAIL);
    }

    /*
     * Assert that variable(s) is a valid URL address
     * @param string|array $vars
     * @return bool|array
     */
    public function url($vars) {
        return $this->filter($vars, FILTER_VALIDATE_URL);
    }
    
    /*
     * Assert that variable(s) is a valid IP. IP can be optional filtered by
     * whether it is ipv4 or ipv6
     * @param string|array $vars
     * @param int $type
     * @return bool|array 
     */
    public function ip($vars, $type = '') { 
        switch($type) {
            case 'ipv4': 
                return $this->filter(	
                                        $vars, 
                                        FILTER_VALIDATE_IP, 
                                        ['flags' => FILTER_FLAG_IPV4]
                                    );
            case 'ipv6':
                return $this->filter(	
                                        $vars, 
                                        FILTER_VALIDATE_IP, 
                                        ['flags' => FILTER_FLAG_IPV6]
                                    );
            default: return $this->filter($vars, FILTER_VALIDATE_IP);
        }
        return $this->filter($vars, FILTER_VALIDATE_URL);
    }

    /*
     * Assert that variable(s) is a floating point number
     * @param float $vars
     * @return bool|array
     */
    public function float($vars) {
        return $this->filter($vars, FILTER_VALIDATE_FLOAT);
    }
    
    /*
     * Assert that variable(s) is a valid username. 
     * Username is defined as alpha-numeric and containing select symbols 
     * with a length that is user specified. 
     * Validation is powered by a RegExp engine. 
     * @param string|array $vars
     * @param int $min
     * @param int $max
     * @return bool|array
     */
    public function username($vars, $min = 3, $max = 16) {
        return $this->validateByRegex($vars, '/^[A-Za-z0-9\.@-_~]{' 
                    . $min . ',' . $max . '}$/'); 
    }
    
    /*
     * Assert that variable(s) is a valid password. 
     * Password is defined as alpha-numeric and containing select symbols 
     * with a length that is user specified. 
     * Validation is powered by a RegExp engine. 
     * @param string|array $vars
     * @param int $min
     * @param int $max
     * @return bool|array
     */
    public function password($vars, $min = 3, $max = 16) {
        return $this->validateByRegex($vars, '/^[A-Za-z0-9#?!@$%^&*-\.]{' 
                    . $min . ',' . $max . '}$/'); 
    }

    /*
     * Assert that variable(s) is a valid complex password that contains atleast
     * one number, one uppercase letter and a symbol. 
     * Password is defined as alpha-numeric and containing select symbols 
     * with a length that is user specified. 
     * Validation is powered by a RegExp engine. 
     * @param string|array $vars
     * @param int $min
     * @param int $max
     * @return bool|array
     */
    public function complexPassword($vars, $min = 6, $max = 25) {
        $regex = '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{' 
                    . $min . ',' . $max . '}$/';
        return $this->validateByRegex($vars, $regex);
    }

    /*
     * Function is an internal function that is designed to return a list of all
     * arguments for a validation without the field name under validation.
     * @param array $args
     * @return bool
     */
    protected function parseFuncArgs($args) {
        return count($args) > 2 ? array_slice($args, 1) : $args;
    }
    
    /*
     * Asserts that variable(s) are within supplied dataset array.
     * This validation function is unique in that information used in 
     * validation, dataSet, is intended to be in array format. This causes an 
     * issue when used in confunction with field screening. parseFuncArgs is 
     * designed to solve this issue.
     * @param string|array $vars
     * @parap array $dataSet
     * @return bool|array
     */
    public function in($vars, $dataSet) {
        $dataSet = $this->parseFuncArgs(func_get_args()); 
        return $this->isArrayForLoop(function($var, $dataSet) {
            return in_array($this->getInput()[$var], $dataSet); 
        }, $vars, $dataSet);	
    }

    /*
     * Asserts that variable(s) are NOT within supplied dataset array.
     * This validation function is unique in that information used in 
     * validation, dataSet, is intended to be in array format. This causes an 
     * issue when used in confunction with field screening. parseFuncArgs is 
     * designed to solve this issue.
     * @param string|array $vars
     * @parap array $dataSet
     * @return bool|array
     */
    public function notIn($vars, $dataSet) {
        $dataSet = $this->parseFuncArgs(func_get_args()); 
        return $this->isArrayForLoop(function($var, $dataSet) {
            return !in_array($this->getInput()[$var], $dataSet); 
        }, $vars, $dataSet);
    }
    
    /*
     * Asserts that variable(s) matches a variable with Conf added on it's name
     * in the subject data.
     * @param string|array $vars
     * @return bool|array
     */
    public function confirmed($vars) {
        $args = $this->flattenArray(func_get_args());
        $results = array_filter($args, function($var) { 
            return $this->getInput()[$var] !== $this->getInput()[$var . 'Conf'];
        });
        return empty($results);
    }
    
    /*
     * Asserts that variable(s) contain a true boolean, 1, or variables that 
     * indicate that the user has accepted.
     * @param string|array $vars
     * @return string|array
     */
    public function accepted($vars) {
        $args = $this->flattenArray(func_get_args());
        $results = array_filter($args, function($var) { 
        return $var === true || $var === 1 || 
            preg_match('/^^[yY](?:a|es|ep){0,2}$$/', $this->getInput()[$var]);
        });
        return !empty($results);
    }
    
    /*
     * Assets that variable(s) is the same as another field. Similar to confirmed
     * except the target field is explicity specified
     * @param string|array $vars
     * @param string $target
     * @return bool
     */
    public function same($vars, $target) {
        if(is_array($vars)) {
            $results = array_filter($vars, function($var) use (&$target) { 
                return $this->getInput()[$var] !== $this->getInput()[$target];
            });
        } else $results = $this->getInput()[$vars] !== $this->getInput()[$target];
        return empty($results);
    }

    /*
     * Check if the URL the variable contains is currently the URL of the page.
     * @param string $var
     * @return bool
     */
    public function activeUrl($var) {
        try {
            if(!is_scalar($var))
                throw new InputException('Variable must be defined as a string.');
            $pre = ['http://', 'https://', 'ftp://'];
            return checkdnsrr(str_replace($pre, '', strtolower($this->getInput()[$var])));	
        } catch(InputException $ex) {
            echo $ex->getMessage();
        }
    }

    public function uniqueInDb() {}
    public function inDb() {}	

    /*
     * Sanitize variable(s) by employing PHP E-mail sanitation filter.
     * @param string|array $vars
     * @return string|array
     */
    public function sanitizeEmail($vars) {
        return $this->filter($vars, FILTER_SANITIZE_EMAIL); 
    }
    
    /*
     * Sanitize variable(s) and return a valid sentence.
     * Anything outside of alpha-numeric characters and a specific set of 
     * symbols are filtered out.
     * @param string|array $vars
     * @return string|array
     */
    public function sanitizeSentence($vars) {
        return $this->sanitizeByRegex($vars, '[^A-Za-z0-9\.,-_\?!]+/');
    }

    /*
     * Sanitize variable(s) by stripping non-numerical related characters.
     * Alphabethical characters outside of E are filtered
     * @param string|array $vars
     * @return string|array
     */
    public function sanitizeNumerical($vars) {
        return $this->sanitizeByRegex($vars, '/[^eE0-9\/\.\%]+/');
    }
    
    /*
     * Sanitize variable(s) and return a valid floating point number
     * @param string|array $vars
     * @return string|array
     */
    public function sanitizeFloat($vars) {
        return $this->filter($vars, FILTER_SANITIZE_NUMBER_FLOAT);
    }

    /*
     * Sanitize variable(s) and return a valid integer
     * @param string|array $vars
     * @return string|array
     */
    public function sanitizeInt($vars) {
        return $this->filter($vars, FILTER_SANITIZE_NUMBER_INT);
    }
    
    /*
     * Sanitize variable(s) by stripping out all non alpha-numeric characters 
     * as well as characters that are not within a specific set of symbols
     * @param string|array $vars
     * @return string|array
     */
    public function sanitizeText($vars) {
        return $this->sanitizeByRegex($vars, '/[^A-Za-z0-9,.!#@$%&_-=+]/m');
    } 

    /*
     * Sanitize variable(s) and return a valid string.
     * @param string|array $vars
     * @return string|array
     */    
    public function sanitizeString($vars) {
        return $this->filter($vars, FILTER_SANITIZE_STRING);
    }
    
    /*
     * Sanitize variable(s) and return a valid URL.
     * @param string|array $vars
     * @return string|array
     */    
    public function sanitizeUrl($vars) {
        return $this->filter($vars, FILTER_SANITIZE_URL);	
    }

    /*
     * Encode and return a safe URL value for usage as slugs
     * @param string|array $vars
     * @return string|array
     */    
    public function urlEncode($vars) {
        return $this->isAssocArrayForLoop(function($var) { 
            return urlencode($this->getInput()[$var]);
        }, $vars); 
    }

    /*
     * Decode an URL string that was encoded using urlencode.
     * @param string|array $vars
     * @return string|array
     */        
    public function urlDecode($vars) {
        return $this->isAssocArrayForLoop(function($var) { 
            return urldecode($this->getInput()[$var]);
        }, $vars);
    }
    
    /*
     * Encode variable(s) using the full special chars filter
     * @param string|array $vars
     * @return string|array 
     */
    public function encode($vars) {
        return $this->filter($vars, FILTER_SANITIZE_FULL_SPECIAL_CHARS);	
    }

     /*
     * Decode variable(s) that are encoded with the full special chars filter
     * @param string|array $vars
     * @return string|array 
     */   
    public function decode($vars) {
        return $this->isAssocArrayForLoop(function($var) { 
            return htmlspecialchars_decode($this->getInput()[$var]);
        }, $vars);
    }
    
    /*
     * Add slashes to variable(s) to prevent against xss attacks 
     * @param string|array $vars
     * @return string|array 
     */
    public function addSlashes($vars) {
        return $this->isAssocArrayForLoop(function($var) { 
            return addslashes($this->getInput()[$var]);
        }, $vars);
    }
    
    /*
     * Strip slashes from variable(s) that had slashes added to it to prevent 
     * against xss 
     * @param string|array $vars
     * @return string|array 
     */
    public function stripSlashes($vars) {
        return $this->isAssocArrayForLoop(function($var) { 
            return stripslashes($this->getInput()[$var]);
        }, $vars);
    }

    /*
     * Internal function that calls the various field validations and applies 
     * failure messaging
     * @param string $funcName
     * @param string|array #parameters
     * @return void
     */
    protected function callValidation($funcName, $parameters) {  
        $results = is_array($parameters) ?
            call_user_func_array([$this, $funcName], $parameters) : 
            call_user_func([$this, $funcName], $parameters);
        if(!$results) $this->addValidationFailure($funcName, $parameters);
    }

    /*
     * Internal function that adds validation messages based on function name,
     * and the specified parameters. Messages are added to a book instance.
     * @param string $funcName
     * @param string|array #parameters
     * @return void
     */
    protected function addValidationFailure($funcName, $parameters) {
        $field = is_array($parameters) ? 
            array_shift($parameters) : (is_scalar($parameters) ? $parameters : NULL);
        $this->failures[$field][$funcName] = $parameters; 
        $message = $this->getMessage($funcName, $field);
        $attribute = $this->getAttribute($field);
        $translated = $this->cipher->decipher($message, $attribute, $parameters);
        $this->book->page($field, $translated);
    }

    /*
     * Internal function that gets either a custom error message placeholder
     * or default presets.
     * @param string $validation
     * @param string $field
     * @return string $message
     */
    protected function getMessage($validation, $field) { 
        $fieldSpecific = $field . ':' . $validation;
        if(isset($this->customMessages[$fieldSpecific])) 
            $message = $this->customMessages[$fieldSpecific];
        elseif(isset($this->customMessages[$validation])) 
            $message = $this->customMessages[$validation];
        else $message = $this->diction->get($validation);
        return $message;
    }

    /*
     * Internal function that gets custom field names if they are set
     * @param string $field
     * @return string
     */
    protected function getAttribute($field) {
        return isset($this->customAttributes[$field]) ? $this->customAttributes[$field] : $field;
    }
    
    /*
     * Get a copy of the message book instance 
     * @return Brandish\Messaging\MessageBook
     */
    public function book() {
        return $this->book;
    }

    /*
     * Screen fields by specified rules that can be entered in a multitude of 
     * formats
     * Ex. Validation::on('ENV')->screen('SERVER_PORT', 'required', 'ip:ipv4', 
     *      'between:5,100', 'in:1,2,3,4,5,6,81');
     * Ex. Validation::on('ENV')->screen('REMOTE_ADDR', 'required', 'ip:ipv4');
     * Ex. Validation::on('ENV')->screen('SERVER_PORT|required|ip:ipv4|between:5,100|
     *      in:1,2,3,4,5,6,80,81', 'REMOTE_ADDR|required|ip:ipv4'); 
     * Ex. Validation::on('ENV')->screen(['SERVER_PORT' => 'required|ip:ipv4
     *      |between:5,100|in:1,2,3,4,5,6,81', 'REMOTE_ADDR' => 'required|ip:ipv4']);
     * Ex. Validation::on('ENV')->screen(['SERVER_PORT' => ['required', ['ip', 'ipv4'], 
     *      ['between', 5, 100], ['in', 1, 2, 3, 4, 5, 6, 81]], 
     *      'REMOTE_ADDR' => ['required', ['ip', 'ipv4']]]);
     * Ex. Validation::on('ENV')->screen(['SERVER_PORT' => ['required', 'ip:ipv4', 
     *      'between:5,100', 'in:1,2,3,4,5,6,81'], 
     *      'REMOTE_ADDR' => ['required', 'ip:ipv4']]);
     * @return $this
     */
    public function screen() { 
        $entries = func_get_args();
        if(!is_array(func_get_arg(0)) && !strpos(func_get_arg(0), '|'))
            $field = array_shift($entries);
        foreach($entries as $entry) {
            if(is_array($entry)) {
                foreach($entry as $field => $args) 
                    $this->constraints[$field] = $this->convert($args);
            } elseif(strpos($entry, '|')) {
                $start = strpos($entry, '|');  
                $field = substr($entry, 0, $start);
                $args = substr($entry, ++$start, strlen($entry));
                $this->constraints[$field] = $this->convert($args); 
            } else {
                $convertedArr = $this->convert($entry);
                $converted = is_array($convertedArr) ? array_shift($convertedArr) : $convertedArr;		
                $this->constraints[$field][] = $converted;
            } 
        }
        return $this;
    }

    /*
     * Internal function that converts that various rules submitted during
     * screening into one format that can be read and executed.
     * @parameter string $entry
     * @return string
     */
    protected function convert($entry) { 
        $constraints = is_string($entry) ? explode('|', $entry) : $entry; 
        foreach($constraints as $constraint) { 
            $args = is_array($constraint) ? $constraint : explode(':', $constraint); 
            if(count($args) == 1 && count($constraints) == 1) $parsed = reset($args);
            elseif(count($args) == 1) $parsed[] = reset($args); 
            else { 
                $validation = array_shift($args);
                if(!strpos(current($args), ',')) { 
                    $options = $args;
                    $parsed[][$validation] = $options;
                } else {
                    $options = explode(',', current($args));
                    $parsed[][$validation] = $options;
                }  
            }  
        } 
        return $parsed;
    }	

    /*
     * Parse all field constraints applied during screening. Once the validation
     * is called, any error messages will be added to a new message book instance 
     * and saved to the this validation instance
     * @return $this
     */
    public function execute() {
        $this->book = new Messaging\MessageBook; 
        if(is_array($this->constraints)) {
            foreach($this->constraints as $field => $constraints) {
                foreach($constraints as $constraint) { 
                    if(is_array($constraint)) {  
                        $funcName = key($constraint);
                        $args = current($constraint);
                        array_unshift($args, $field);
                    } else {  
                        $funcName = $constraint;
                        $args = $field; 
                    }  
                    if(method_exists($this, $funcName))
                        $this->results[$field] = $this->callValidation($funcName, $args); 
                }
            }	
        }
        return $this;	
    }

    /*
     * Add custom placeholder error messages that correspond to field names in
     * an associative array
     * @param array $messages
     * @return $this
     */
    public function messages($messages) { 
        try {
            if(!is_array($messages)) 
                throw new InputException("Message must be defined in an associative array.");
            $this->customMessages = $messages;
            return $this;
        } catch(InputException $ex) {
            echo $ex->getMessage();
        }
    }

    /*
     * Add custom field names that correspond to proper names in error messages 
     * in an associative array
     * @param array $messages
     * @return $this
     */
    public function attributes($attributes) {
        try {
            if(!is_array($attributes)) 
                throw new InputException("Field nomenclature must be defined in an associative array.");
            $this->customAttributes = $attributes;
            return $this;
        } catch(InputException $ex) {
            echo $ex->getMessage();
        }
    }
}
