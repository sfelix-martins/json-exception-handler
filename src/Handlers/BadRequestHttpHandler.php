<?php

namespace SMartins\Exceptions\Handlers;

use SMartins\Exceptions\JsonApi\Error;
use SMartins\Exceptions\JsonApi\Source;

class BadRequestHttpHandler extends AbstractHandler
{
    /**
     * {@inheritDoc}
     */
    public function handle()
    {
        return (new Error)->setStatus(400)
            ->setCode($this->getCode('bad_request'))
            ->setSource((new Source())->setPointer($this->getDefaultPointer()))
            ->setTitle($this->getDefaultTitle())
            ->setDetail($this->exception->getMessage());
    }
}
