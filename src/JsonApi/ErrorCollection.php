<?php

namespace SMartins\Exceptions\JsonApi;

use Illuminate\Support\Collection;

class ErrorCollection extends Collection
{
    /**
     * The HTTP status code applicable to this problem, expressed as a string value.
     *
     * @var string
     */
    protected $statusCode;

    /**
     * The HTTP headers on response.
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Returns the status code.
     *
     * @return string
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Returns response headers.
     *
     * @return array headers
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Set the status code.
     *
     * @param string $statusCode
     *
     * @return self
     */
    public function setStatusCode(string $statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Set the headers of response.
     *
     * @param array $headers
     * @return self
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Validate the content of items. All item should to be an instances of Error.
     *
     * @return self
     *
     * @throws \SMartins\Exceptions\JsonApi\InvalidContentException
     */
    public function validated()
    {
        foreach ($this->items as $item) {
            if ($item instanceof Error === false) {
                throw new InvalidContentException('All items on '.self::class.' must to be instances of '.Error::class, 1);
            }
        }

        return $this;
    }
}
