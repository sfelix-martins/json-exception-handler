<?php

namespace SMartins\JsonHandler;

use SMartins\JsonHandler\Responses\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait ModelNotFoundHandler
{
    /**
     * Set the response if Exception is ModelNotFound.
     *
     * @param ModelNotFoundException $exception
     */
    public function modelNotFoundException(ModelNotFoundException $exception)
    {
        $entitie = $this->extractEntitieName($exception->getModel());
        $ids = implode($exception->getIds(), ',');

        $this->response->setMessage($entitie.' not found. #'.$ids);
        $this->response->setCode($this->getCode('model_not_found'));
        $this->response->setDescription($exception->getMessage());
        $this->response->setHttpCode(404);
    }

    /**
     * Get entitie name based on model path to mount the message.
     *
     * @param string $model
     *
     * @return string
     */
    public function extractEntitieName($model)
    {
        $entitieName = explode('\\', $model);

        return end($entitieName);
    }
}
