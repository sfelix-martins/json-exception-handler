<?php

namespace SMartins\JsonHandler;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use SMartins\JsonHandler\Responses\Response;

trait JsonHandler
{
    use ValidationHandler, ModelNotFoundHandler;

    public $response;

    public function setDefaultResponse(Exception $exception)
    {
        $description = $exception->getMessage().
            ' line '. $exception->getLine().
            ' in '. basename($exception->getFile());

        $response = new Response;
        $response->setMessage(class_basename($exception));
        $response->setCode(1);
        $response->setDescription($description);
        $response->setHttpCode(500);

        return $this->response = $response;
    }

    public function jsonResponse(Exception $exception)
    {
        $this->setDefaultResponse($exception);

        if ($exception instanceOf ValidationException) {
            $this->validationException($exception);
        } elseif ($exception instanceOf ModelNotFoundException) {
            $this->modelNotFoundException($exception);
        }

        return response()->json(
            $this->response->toArray(), 
            $this->response->getHttpCode()
        );
    }
}