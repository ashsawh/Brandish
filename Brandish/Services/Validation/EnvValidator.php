<?php namespace Brandish\Services\Validation;
/*
 * @author ashwin@answersnetwork.com   
 * Class handles validation of the $_ENV superglobal. It adheres to the 
 * IValidator interface contract. 
 * 
 */

class EnvValidator extends BaseInputValidator {
    /*
     * @global Contains the PHP integer reference that is employed by 
     * filter_input
     */
    const TYPE = INPUT_ENV;
	
    /*
     * Get the contents of the $_ENV superglobal
     * @return array
     */
    protected function getInput() {
        return $_ENV;
    }
}