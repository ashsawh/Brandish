<?php namespace Brandish\Services\Messaging;

interface IPlaceholderCipher {
    public function decipher($message, $field, $parameters);
}