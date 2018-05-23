<?php

namespace SMartins\Exceptions\Response;

interface ErrorHandledCollectionInterface extends ErrorCollectionInterface
{
    /**
     * Check if content on collection contains only the type class passed on
     * $type parameter. If one item on collection was different of type an
     * exception is thrown.
     *
     * @param  string $type
     * @return self
     *
     * @throws \SMartins\Exceptions\Response\InvalidContentException
     */
    public function validatedContent(string $type): self;
}
