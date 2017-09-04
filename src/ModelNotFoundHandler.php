<?php

namespace SMartins\JsonHandler;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use SMartins\JsonHandler\Responses\Response;

trait ModelNotFoundHandler
{
    public function modelNotFoundException(ModelNotFoundException $exception)
    {
        $entitie = $this->extractEntitieName($exception->getModel());
        $ids = implode($exception->getIds(), ',');
        
        $this->response->setMessage($entitie. ' not found. #'. $ids);
        $this->response->setCode($this->getCode('model_not_found'));
        $this->response->setDescription($exception->getMessage());
        $this->response->setHttpCode(404);
    }

    public function extractEntitieName($model)
    {
        $entitieName = explode('\\', $model);
        return end($entitieName);
    }
}
