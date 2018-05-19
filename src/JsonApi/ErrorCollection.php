<?php

namespace SMartins\Exceptions\JsonApi;

use InvalidArgumentException;
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
     * Get the status code.
     *
     * @return string
     */
    public function getStatusCode()
    {
        return $this->statusCode;
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
     * Validate the content of items. All item should to be an instances of Error.
     *
     * @return self
     *
     * @throws \SMartins\Exceptions\JsonApi\CollectionInvalidContent
     */
    public function validate()
    {
        foreach ($this->items as $item) {
            if ($item instanceof Error === false) {
                throw new InvalidContentException('All items on '.self::class.' must to be instances of '.Error::class, 1);
            }
        }

        return $this;
    }
}
