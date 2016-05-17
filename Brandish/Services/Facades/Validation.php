<?php namespace Brandish\Services\Facades;
use Brandish\Services\Validation as Validator;
use Brandish\Services\Messaging as Messaging;
use Brandish\Services\Containers as Containers;

/*
 * @author ashwin@answersnetwork.com  
 * @static
 * Factory class that creates Input validation sub-classes or custom validation
 * based on data supplied. 
 * 
 */

class Validation {
    /*
     * Factory function that creates CustomValidator or Input Validator sub-classes
     * @static
     * @param string $subject
     * @param array $messages optional parameter that that creates the classes with
     *      custom placeholder error messages.
     * @return void
     */
    public static function on($subject, $messages = []) {
        #$locale = empty(getLocale()) ? 'en_US' : LOCALE;
        $locale = 'en_US';
	$cipher = new Messaging\PlaceholderCipher;
	$book = new Messaging\MessageBook; 
	$lang = '..' . DIRECTORY_SEPARATOR . 'Validation' .
             DIRECTORY_SEPARATOR . 'Diction' . DIRECTORY_SEPARATOR .
       	     $locale . DIRECTORY_SEPARATOR . 'ValidationDiction.php';

	$lang = '/home/www/sandbox.com/public_html/Brandish/Services/Validation/Diction/en_US/ValidationDiction.php';
	$diction = new Containers\Diction(include($lang), $locale);
        if(is_subclass_of($type, 'Brandish\Services\Validation\AbstractInputValidator')) {
	    $type = 'Brandish\Services\Validation\\' . ucfirst(strtolower($subject)) . 'Validator';
            return $type::make($diction, $book, $cipher, $messages); }
        else return new Validator\DataValidator($subject, $diction, $book, $cipher, $messages);
    }
}
