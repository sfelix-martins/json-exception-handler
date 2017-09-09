<?php

namespace SMartins\JsonHandler\Responses;

class Response
{
    protected $message;

    protected $httpCode;

    protected $description;

    protected $errors;

    protected $code;

    public function getMessage()
    {
        return $this->message;
    }

    public function getHttpCode()
    {
        return $this->httpCode;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function setHttpCode($httpCode)
    {
        $this->httpCode = $httpCode;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function toArray()
    {
        $response = [
            'message'   => $this->getMessage(),
            'code'      => $this->getCode(),
        ];
        if (!is_null($this->getErrors())) {
            $response['errors'] = $this->getErrors();
        }
        if (!is_null($this->getDescription())) {
            $response['description'] = $this->getDescription();
        }

        return $response;
    }
}
