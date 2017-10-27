<?php

namespace SMartins\JsonHandler;

use Laravel\Passport\Exceptions\MissingScopeException;

trait MissingScopeHandler
{
    public function missingScopeException(MissingScopeException $exception)
    {
        $error = [[
            'status'    => 403,
            'code'      => $this->getCode('missing_scope'),
            'source'    => ['pointer' => $exception->getFile().':'.$exception->getLine()],
            'title'     => 'missing_scope',
            'detail'    => $exception->getMessage(),
        ]];

        $this->jsonApiResponse->setStatus(403);
        $this->jsonApiResponse->setErrors($error);
    }
}
