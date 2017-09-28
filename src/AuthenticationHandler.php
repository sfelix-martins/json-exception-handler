<?php

namespace SMartins\JsonHandler;

use Illuminate\Auth\AuthenticationException;

trait AuthenticationHandler
{
    public function authenticationException(AuthenticationException $exception)
    {
        $error = [[
            'status'    => 401,
            'code'      => $this->getCode('authentication'),
            'source'    => ['pointer' => ''],
            'title'     => $exception->getMessage(),
            'detail'    => 'The request was made by an unauthenticated user.',
        ]];

        $this->jsonApiResponse->setStatus(401);
        $this->jsonApiResponse->setErrors($error);
    }
}
