<?php

require('helpers.php');

use App\Core\Json\JsonSearch;
use App\Core\Response;
use App\Core\Request\Request;

header('Content-Type: application/json');
$request = Request::getRequest();
$db = json_decode(
    file_get_contents(DB_PATH),
    true
);
$jsonSearch = new JsonSearch($db);

if ($jsonSearch->resourceExists($request['resource'])) {
    switch ($request['method']) {
        case 'GET':
            (new Response($db, $jsonSearch))
                ->getResponse($request);
            break;
        
        case 'POST':
            (new Response($db, $jsonSearch))
                ->postResponse($request, ['name','genre']);
            break;
        
        case 'PUT':
            (new Response($db, $jsonSearch))
                ->putResponse($request);
            break;
        
        case 'DELETE':
            (new Response($db, $jsonSearch))
                ->deleteResponse($request);
            break;

        default:
            Response::resourceError(
                "Request method is not supported", 
                405
            );
            break;
    }
} else {
    Response::resourceError(
        "Resource Not Found", 
        404
    );
}