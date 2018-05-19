<?php

namespace SMartins\Exceptions\Handlers;

use SMartins\Exceptions\JsonApi\Error;
use SMartins\Exceptions\JsonApi\Source;

class Handler extends AbstractHandler
{
    /**
     * {@inheritDoc}
     */
    public function handle()
    {
        return (new Error)->setStatus($this->getStatusCode())
            ->setCode($this->getCode())
            ->setSource((new Source())->setPointer($this->getDefaultPointer()))
            ->setTitle($this->getDefaultTitle())
            ->setDetail($this->exception->getMessage());
    }
}
