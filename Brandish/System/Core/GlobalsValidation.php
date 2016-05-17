<?php namespace Rubicon\System\Core;

class Validator implements IGlobalsValidator {
    public function sanitizeURI($uri) {
        return filter_var(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);
    }
}
