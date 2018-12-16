<?php

namespace App\Core\Request;

/**
 * Trait for handle request resource errors
 */
trait RequestResourceError
{
    public static function resourceError($message, $error) {
        echo "{\"Error\": \"${message}\", \"Status\": ${error}}";
        http_response_code($error);
        die();
    }
}
