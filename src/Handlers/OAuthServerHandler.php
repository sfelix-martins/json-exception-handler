<?php

namespace SMartins\Exceptions\Handlers;

use SMartins\Exceptions\JsonApi\Error;
use SMartins\Exceptions\JsonApi\Source;

class OAuthServerHandler extends AbstractHandler
{
    /**
     * {@inheritDoc}
     */
    public function handle()
    {
        return (new Error)->setStatus($this->getHttpStatusCode())
            ->setCode($this->getCode())
            ->setSource((new Source())->setPointer($this->getDefaultPointer()))
            ->setTitle($this->exception->getErrorType())
            ->setDetail($this->exception->getMessage());
    }
}
