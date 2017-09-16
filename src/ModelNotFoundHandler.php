<?php

namespace SMartins\JsonHandler;

use Illuminate\Database\Eloquent\ModelNotFoundException;

trait ModelNotFoundHandler
{
    /**
     * Set the response if Exception is ModelNotFound
     *
     * @param  ModelNotFoundException $exception
     */
    public function modelNotFoundException(ModelNotFoundException $exception)
    {
        $entitie = $this->extractEntitieName($exception->getModel());
        $ids = implode($exception->getIds(), ',');

        $error = [[
            'status'    => 404,
            'code'      => $this->getCode('model_not_found'),
            'source'    => ['pointer' => 'data/id'],
            'title'     => $entitie. ' not found.',
            'detail'    => $exception->getMessage(),
        ]];

        $this->jsonApiResponse->setStatus(404);
        $this->jsonApiResponse->setErrors($error);
    }

    /**
     * Get entitie name based on model path to mount the message.
     *
     * @param  string $model
     * @return string
     */
    public function extractEntitieName($model)
    {
        $entitieName = explode('\\', $model);
        return end($entitieName);
    }
}
