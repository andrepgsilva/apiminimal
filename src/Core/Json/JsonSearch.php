<?php

namespace App\Core\Json;
use App\Core\Response;

class JsonSearch 
{
    private $dbJson;

    public function __construct($dbJson) {
        $this->dbJson = $dbJson;
    }

    /**
     * Verify resource existence
     */
    function resourceExists($resource)
    {
        foreach($this->dbJson as $key => $element) {
            if ($key == $resource) {
                return true;
            }
        }
    }

    /**
     * Find element in json db using a parameter
     */
    function findBy($param, $value, $arrayKey) 
    {
        if (count($this->dbJson[$arrayKey]) === 0) {
            return false;
        }

        foreach($this->dbJson[$arrayKey] as $record) {
            $element = $record[$param];
            if ($param != 'id') {
                $element = strtolower($record[$param]);
            }
            if ($element == strtolower($value)) {
                return $record;
            }
        }
    }

    /**
     * Search for resource and return id
     */
    public function searchResourceIndex($request, $dbResource)
    {
        $idSearched = array_search(
            (int)$request['param'], 
            array_column($dbResource, 'id')
        );

        if ($idSearched) {
            return $idSearched;
        }
        
        Response::resourceError(
            "Resource Not Found", 
            404
        );
    }
}