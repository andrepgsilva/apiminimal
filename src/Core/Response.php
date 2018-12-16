<?php

namespace App\Core;
use App\Core\Request\BodyRequest;
use App\Core\Request\RequestResourceError;

class Response 
{
    private $db;
    private $searcher;
    private $standardInput;

    use RequestResourceError;

    public function __construct($db, $searcher)
    {
        $this->db = $db;
        $this->searcher = $searcher;
        $this->standardInput = file_get_contents('php://input');
    }

    public function getResponse($request) {
        if (! $request['param']) {
            echo json_encode($this->db[$request['resource']]);
        } else {
            $jsonElement = $this->searcher->findBy(
                'id',
                $request['param'],
                $request['resource']
            );
            if ($jsonElement) {
                echo json_encode($jsonElement);
                http_response_code(200);
            } else {
                static::resourceError("Resource Not Found", 404);
            }
        }
    }

    public function postResponse($request, $validate)
    {
        $body = json_decode($this->standardInput, true);
        // Verify body properties conditions.
        $body = BodyRequest::canApplyBody($body);
        // If resource exists, error returned + app die
        BodyRequest::resourceExists($body, $request, $this->searcher);
        // If Body doesn't meets the requirements, error returned + app die
        BodyRequest::isEnoughBody($body, $validate);
        // If necessary, set a new id
        $body['id'] = time();

        $this->db[$request['resource']][] = $body;
        file_put_contents(DB_PATH, json_encode($this->db));
        http_response_code(201);
    }

    public function putResponse($request)
    {
        // If resource doesn't exists, error returned + app die
        $idSearched = $this->searcher->searchResourceIndex(
            $request, 
            $this->db[$request['resource']]
        );
        if ($idSearched) {
            $resource = $this->db[$request['resource']][$idSearched];
            $bodyInput = json_decode($this->standardInput, true);
            $resource = BodyRequest::canApplyBody($bodyInput);
            $resource['id'] = (int)$request['param'];
            foreach($resource as $key => $value) {
                $this->db[$request['resource']][$idSearched][$key] = $value;
            }
            file_put_contents(DB_PATH, json_encode($this->db));
        }
    }

    public function deleteResponse($request)
    {
        $id = $this->searcher->searchResourceIndex(
            $request, 
            $this->db[$request['resource']]
        );
        if (! $id) {
            Response::resourceError(
                "Resource Not Found", 
                404
            );        
        }
        unset($this->db[$request['resource']][$id]);
        file_put_contents(DB_PATH, json_encode($this->db));
        http_response_code(204);
    }
}