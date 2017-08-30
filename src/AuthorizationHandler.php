<?php

namespace SMartins\JsonHandler;

use SMartins\JsonHandler\Responses\Response;
use Illuminate\Auth\Access\AuthorizationException;

trait AuthorizationHandler
{
    public function authorizationException(AuthorizationException $exception)
    {
        $response = new Response;
        $response->setMessage($exception->getMessage());
        $response->setCode(config('json-exception-handler.codes.authorization'));
        $response->setHttpCode(403);

        return $this->response = $response;
    }
}