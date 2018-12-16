<?php

namespace App\Core\Request;

class Request 
{
    /**
     * Split Uri and return informations in array
     */
    private static function splitUri() 
    {
        $splitedUri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        array_unshift($splitedUri, $_SERVER['REQUEST_METHOD']);
        return $splitedUri;
    }

    /**
     *  Return informations about request that was splitted
     */
    public static function getRequest() {
        $request = static::splitUri();
        return [
            'uri' => $request[1],
            'method' => $request[0],
            'resource' => isset($request[2]) ? $request[2] : '',
            'param' => isset($request[3]) ? $request[3] : ''
        ];
    }
}