<?php

namespace SMartins\JsonHandler;

use Illuminate\Database\Eloquent\ModelNotFoundException;

trait ModelNotFoundHandler
{
    /**
     * Set the response if Exception is ModelNotFound.
     *
     * @param  ModelNotFoundException $exception
     */
    public function modelNotFoundException(ModelNotFoundException $exception)
    {
        $entity = $this->extractEntityName($exception->getModel());

        $ids = implode($exception->getIds(), ',');

        $error = [[
            'status'    => 404,
            'code'      => $this->getCode('model_not_found'),
            'source'    => ['pointer' => 'data/id'],
            'title'     => $exception->getMessage(),
            'detail'    => __('exception::exceptions.model_not_found.title', ['model' => $entity]),
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
    public function extractEntityName($model)
    {
        $classNames = (array) explode('\\', $model);

        $entityName = end($classNames);

        if ($this->entityHasTranslation($entityName)) {
            return __('exception::exceptions.models.'.$entityName);
        }

        return $entityName;
    }

    /**
     * Check if entity returned on ModelNotFoundException has translation on
     * exceptions file
     * @param  string $entityName The model name to check if has translation
     * @return bool               Has translation or not
     */
    public function entityHasTranslation(string $entityName): bool
    {
        $hasKey = in_array($entityName, $this->translationModelKeys());

        if ($hasKey) {
            return ! empty($hasKey);
        }

        return false;
    }

    /**
     * Get the models keys on exceptions lang file
     * @return array An array with keys to translate
     */
    private function translationModelKeys(): array
    {
        return array_keys(__('exception::exceptions.models'));
    }
}
