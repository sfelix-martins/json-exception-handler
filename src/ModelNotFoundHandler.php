<?php

namespace SMartins\JsonHandler;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use SMartins\JsonHandler\Responses\Response;

trait ModelNotFoundHandler
{
    public function modelNotFoundException(ModelNotFoundException $e)
    {
        $entitie = $this->extractEntitieName($e->getModel());
        $ids = implode($e->getIds(), ',');
        
        $response = new Response;
        $response->setMessage($entitie. ' not found. #'. $ids);
        $response->setCode(124);
        $response->setDescription($e->getMessage());
        $response->setHttpCode(404);

        return $this->response = $response;
    }

    public function extractEntitieName($model)
    {
        $entitieName = explode('\\', $model);
        return end($entitieName);
    }
}