<?php

namespace App\Core\Request;
use App\Core\Response;

/**
 * Handler for request body
 */
class BodyRequest
{

    /**
     * Verify resource existence
     */
    public static function resourceExists($body, $request, $searcher)
    {

        $jsonElement = $searcher->findBy(
            'name',
             $body['name'], 
             $request['resource']
        );

        if ($jsonElement) {
            Response::resourceError(
                "Resource Already Exists", 
                409
            );
        }
    }

    /**
     * Restrict post to only one resource
     */
    public static function restrictPost($body)
    {
        if (array_key_exists(1, $body)) {
            $body = $body[0];
        }
        return $body;
    }

    /**
     * Verify if body meets the requirements 
     */
    public static function isEnoughBody($body, $validate)
    {
        if (! (new Validation($validate))->validate($body)) {
            Response::resourceError(
                "The body doesn't have enough information", 
                409
            );
        }
    }

    /**
     * Verify body properties conditions
     */
    public static function canApplyBody($bodyInput)
    {
        $body = static::restrictPost($bodyInput);
        // false = null, false, ''
        if (in_array(false, $body) || count($body) == 0) {
            Response::resourceError(
                "The body doesn't have enough information", 
                409
            );
        }
        return $body;
    }
}