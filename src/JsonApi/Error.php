<?php

namespace SMartins\Exceptions\JsonApi;

use SMartins\Exceptions\JsonApi\Links;
use SMartins\Exceptions\JsonApi\Source;
use Illuminate\Contracts\Support\Arrayable;
use SMartins\Exceptions\Traits\NotNullArrayable;

class Error implements Arrayable
{
    use NotNullArrayable;

    /**
     * A unique identifier for this particular occurrence of the problem.
     *
     * @var string
     */
    protected $id;

    /**
     * @var \SMartins\Exceptions\JsonApi\Links
     */
    protected $links;

    /**
     * The HTTP status code applicable to this problem, expressed as a string value.
     *
     * @var string
     */
    protected $status;

    /**
     * An application-specific error code, expressed as a string value.
     *
     * @var string
     */
    protected $code;

    /**
     * A short, human-readable summary of the problem that SHOULD NOT change from
     * occurrence to occurrence of the problem, except for purposes of localization.
     *
     * @var string
     */
    protected $title;

    /**
     * A human-readable explanation specific to this occurrence of the problem.
     * Like title, this field’s value can be localized.
     *
     * @var string
     */
    protected $detail;

    /**
     * An object containing references to the source of the error.
     *
     * @var \SMartins\Exceptions\JsonApi\Source
     */
    protected $source;

    /**
     * Get a unique identifier for this particular occurrence of the problem.
     *
     * @return  string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set a unique identifier for this particular occurrence of the problem.
     *
     * @param  string  $id
     *
     * @return  self
     */
    public function setId(string $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of links
     *
     * @return  \SMartins\Exceptions\JsonApi\Links
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Set the value of links
     *
     * @param  \SMartins\Exceptions\JsonApi\Links  $links
     *
     * @return  self
     */
    public function setLinks(Links $links)
    {
        $this->links = $links;

        return $this;
    }

    /**
     * Get the HTTP status code applicable to this problem, expressed as a string value.
     *
     * @return  string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the HTTP status code applicable to this problem, expressed as a string value.
     *
     * @param  string  $status
     *
     * @return  self
     */
    public function setStatus(string $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get an application-specific error code, expressed as a string value.
     *
     * @return  string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set an application-specific error code, expressed as a string value.
     *
     * @param  string  $code
     *
     * @return  self
     */
    public function setCode(string $code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get occurrence to occurrence of the problem, except for purposes of localization.
     *
     * @return  string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set occurrence to occurrence of the problem, except for purposes of localization.
     *
     * @param  string  $title
     *
     * @return  self
     */
    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get like title, this field’s value can be localized.
     *
     * @return  string
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Set like title, this field’s value can be localized.
     *
     * @param  string  $detail
     *
     * @return  self
     */
    public function setDetail(string $detail)
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * Get an object containing references to the source of the error.
     *
     * @return  \SMartins\Exceptions\JsonApi\Source
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set an object containing references to the source of the error.
     *
     * @param  \SMartins\Exceptions\JsonApi\Source  $source
     *
     * @return  self
     */
    public function setSource(Source $source)
    {
        $this->source = $source;

        return $this;
    }
}
