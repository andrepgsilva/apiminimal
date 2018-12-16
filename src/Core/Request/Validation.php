<?php

namespace App\Core\Request;

class Validation
{
    private $attributes;

    /**
     * Get Attributes for verification
     */
    public function __construct($attributes) {
        $this->attributes = $attributes;
    }

    public function validate($body) 
    {
        foreach($this->attributes as $attr) {
            if (! array_key_exists($attr, $body)) {
                return false;
            }
        }
        return true;
    }
}