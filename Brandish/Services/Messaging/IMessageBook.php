<?php namespace Brandish\Services\Messaging;

/*
 * @author ashwin@answersnetwork.com  
 * @static
 * Factory class that creates Input validation sub-classes or custom validation
 * based on data supplied. 
 * 
 */

interface IMessageBook {
    public function format($format = ''); 
    public function get($field, $format = '');
    public function has($field);
    public function all($format = '');
    public function count($field = '');
    public function first($field);
    public function raw($field = '');
    public function page($title, $message);
} 