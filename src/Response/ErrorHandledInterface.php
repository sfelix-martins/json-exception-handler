<?php

namespace SMartins\Exceptions\Response;

interface ErrorHandledInterface
{
    /**
     * Adds itself to a error collection and returns the instance of collection.
     *
     * @todo Maybe pass this method to another interface.
     *
     * @return \SMartins\Exceptions\Response\ErrorHandledCollectionInterface
     */
    public function toCollection(): ErrorHandledCollectionInterface;

    /**
     * Get the HTTP status code applicable to this problem, expressed as a string value.
     *
     * @return  string
     */
    public function getStatus(): string;
}
