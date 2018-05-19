<?php

namespace SMartins\Exceptions\Handlers;

use SMartins\Exceptions\JsonApi\Error;
use SMartins\Exceptions\JsonApi\Source;

class AuthenticationHandler extends AbstractHandler
{
    /**
     * {@inheritDoc}
     */
    public function handle()
    {
        return (new Error)->setStatus(401)
            ->setCode($this->getCode('authentication'))
            ->setSource((new Source())->setPointer($this->getDefaultPointer()))
            ->setTitle($this->getDefaultTitle())
            ->setDetail(__('exception::exceptions.authentication.detail'));
    }
}
