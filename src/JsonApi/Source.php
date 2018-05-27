<?php

namespace SMartins\Exceptions\JsonApi;

use Illuminate\Contracts\Support\Arrayable;
use SMartins\Exceptions\Traits\NotNullArrayable;

class Source implements Arrayable
{
    use NotNullArrayable;

    /**
     * A JSON Pointer [RFC6901] to the associated entity in the request document
     * [e.g. "/data" for a primary data object, or "/data/attributes/title" for
     * a specific attribute].
     *
     * @var string
     */
    protected $pointer;

    /**
     * A string indicating which URI query parameter caused the error.
     *
     * @var string
     */
    protected $parameter;

    /**
     * Get pointer.
     *
     * @return  string
     */
    public function getPointer()
    {
        return $this->pointer;
    }

    /**
     * Set pointer.
     *
     * @param  string  $pointer
     *
     * @return  self
     */
    public function setPointer(string $pointer)
    {
        $this->pointer = $pointer;

        return $this;
    }

    /**
     * Get parameter.
     *
     * @return  string
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * Set parameter.
     *
     * @param  string  $parameter
     *
     * @return  self
     */
    public function setParameter(string $parameter)
    {
        $this->parameter = $parameter;

        return $this;
    }
}
