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
            'source'    => ['pointer' => $exception->getFile().':'.$exception->getLine()],
            'title'     => $exception->getMessage(),
            'detail'    => __('exception::exceptions.authentication.detail'),
        ]];

        $this->jsonApiResponse->setStatus(401);
        $this->jsonApiResponse->setErrors($error);
    }
}
