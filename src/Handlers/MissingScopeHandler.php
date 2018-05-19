<?php

namespace SMartins\Exceptions\Handlers;

use SMartins\Exceptions\JsonApi\Error;
use SMartins\Exceptions\JsonApi\Source;

class MissingScopeHandler extends AbstractHandler
{
    /**
     * {@inheritDoc}
     */
    public function handle()
    {
        return (new Error)->setStatus(403)
            ->setCode($this->getCode('missing_scope'))
            ->setSource((new Source())->setPointer($this->getDefaultPointer()))
            ->setTitle($this->getDefaultTitle())
            ->setDetail($this->exception->getMessage());
    }
}
