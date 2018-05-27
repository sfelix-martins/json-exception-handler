<?php

namespace SMartins\Exceptions\Response;

use Illuminate\Contracts\Support\Arrayable;

interface ErrorCollectionInterface extends Arrayable
{
    /**
     * Returns response headers.
     *
     * @return array Response headers
     */
    public function getHeaders();

    /**
     * Set HTTP status code of response.
     *
     * @param string $statusCode
     *
     * @return self
     */
    public function setStatusCode(string $statusCode);

    /**
     * Get the HTTP status code.
     *
     * @return string|null
     */
    public function getStatusCode();
}
