<?php

namespace SMartins\JsonHandler;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use SMartins\JsonHandler\Responses\Response;

trait JsonHandler
{
    use ValidationHandler, ModelNotFoundHandler, AuthorizationHandler;

    public $response;

    public function setDefaultResponse(Exception $exception)
    {
        $description = $exception->getMessage().
            ' line '. $exception->getLine().
            ' in '. basename($exception->getFile());

        $response = new Response;
        $response->setMessage(class_basename($exception));
        $response->setCode(config('json-exception-handler.codes.default'));
        $response->setDescription($description);
        $response->setHttpCode(config('json-exception-handler.http_code'));

        return $this->response = $response;
    }

    public function jsonResponse(Exception $exception)
    {
        $this->setDefaultResponse($exception);

        if ($exception instanceof ValidationException) {
            $this->validationException($exception);
        } elseif ($exception instanceof ModelNotFoundException) {
            $this->modelNotFoundException($exception);
        } elseif ($exception instanceof AuthorizationException) {
            $this->authorizationException($exception);
        }

        return response()->json(
            $this->response->toArray(),
            $this->response->getHttpCode()
        );
    }
}
