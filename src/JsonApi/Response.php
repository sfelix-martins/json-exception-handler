<?php

namespace SMartins\Exceptions\JsonApi;

use Illuminate\Http\JsonResponse;

class Response
{
    /**
     * The HTTP status code.
     *
     * @var int
     */
    protected $status;

    /**
     * The errors on response.
     *
     * @var \SMartins\Exceptions\JsonApi\ErrorCollection
     */
    protected $errors;

    /**
     * Create new JsonApi response passing the errors.
     *
     * @param \SMartins\Exceptions\JsonApi\ErrorCollection $errors
     */
    public function __construct(ErrorCollection $errors)
    {
        $this->errors = $errors;

        $this->setStatus((int) $this->errors->getStatusCode());
    }

    /**
     * Get the HTTP status code.
     *
     * @return  int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the HTTP status code.
     *
     * @param  int  $status  The HTTP status code.
     *
     * @return  self
     */
    public function setStatus(int $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the errors on response.
     *
     * @return  array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @return string
     */
    public function json()
    {
        return new JsonResponse(
            ['errors' => $this->getErrors()->toArray()],
            $this->getStatus()
        );
    }
}
