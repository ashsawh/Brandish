<?php namespace Brandish\Services\Validation;
/* 
 * Contract for main validation and filtering functions employed by field  
 * validation and sanization. 
 */
interface IValidator {
    /*
     * Check if variable(s) exist
     * @param array|string $vars
     * @return bool
     */
    public function exists($vars);
    /*
     * Check if variable contains a specified variable
     * @param array|string $vars
     * @param string $value
     * @return bool
     */
    public function contains($vars, $value);
    /*
     * Validate variable by regexp
     * @param array|string $regex
     * @param string $regex
     * @return mixed 
     */
    public function validateByRegex($vars, $regex);
    /*
     * Sanitize variable by regex
     * @param array|string $vars
     * @param string $regex
     * @return mixed
     */
    public function sanitizeByRegex($vars, $regex);
    /*
     * Filter variable by standard PHP filters, flags and options
     * @param array|string $vars
     * @param int filter global
     * @param array|string options associative array or string
     * @return mixed 
     */
    public function filter($vars, $filter, $options = '');
    /*
     * Filter variables by specified definitions. Definitions is an associative
     * array
     * @param array $definitions
     * @return mixed
     */
    public function filterByArray($definitions);
}