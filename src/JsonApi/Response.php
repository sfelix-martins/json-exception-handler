<?php

namespace SMartins\Exceptions\JsonApi;

use InvalidArgumentException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

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
     * @param mixed $errors
     */
    public function __construct($errors)
    {
        if ($errors instanceof ErrorCollection) {
            $this->errors = $errors;
        } elseif (is_array($errors) || $errors instanceof Collection) {
            $this->errors = new ErrorCollection($errors);
        } elseif ($errors instanceof Error) {
            $this->errors = (new ErrorCollection)->push($errors);
            $this->errors->setStatusCode($errors->getStatus());
        }

        if (! $this->errors instanceof ErrorCollection) {
            throw new InvalidArgumentException('The errors must be an array, '.Collection::class.','.Error::class.' or '.ErrorCollection::class.'.');
        }

        $this->errors->validate();

        $this->setStatus($this->errors->getStatusCode());
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
     * @param  int  $options
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
