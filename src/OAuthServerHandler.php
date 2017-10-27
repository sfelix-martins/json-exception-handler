<?php

namespace SMartins\JsonHandler;

use League\OAuth2\Server\Exception\OAuthServerException;

trait OAuthServerHandler
{
    public function oAuthServerException(OAuthServerException $exception)
    {
        $statusCode = $exception->getHttpStatusCode();

        $error = [[
            'status'    => $statusCode,
            'code'      => $this->getCode('not_found_http'),
            'source'    => ['pointer' => $exception->getFile().':'.$exception->getLine()],
            'title'     => $exception->getErrorType(),
            'detail'    => $exception->getMessage(),
        ]];

        $this->jsonApiResponse->setStatus($statusCode);
        $this->jsonApiResponse->setErrors($error);
    }
}
