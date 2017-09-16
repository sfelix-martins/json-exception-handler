<?php

namespace SMartins\JsonHandler\Responses;

class JsonApiResponse
{
    protected $status;

    protected $errors;

    public function getStatus()
    {
        return $this->status;
    }

    public function status()
    {
        return $this->getStatus();
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function errors()
    {
        return $this->getErrors();
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    public function toArray()
    {
        return ['errors' => $this->errors()];
    }
}
