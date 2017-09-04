<?php

namespace SMartins\JsonHandler;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use SMartins\JsonHandler\Responses\Response;

trait JsonHandler
{
    use ValidationHandler, ModelNotFoundHandler, AuthorizationHandler, NotFoundHttpHandler;

    public $response;

    private $exception;

    public function setDefaultResponse()
    {
        $this->response->setMessage($this->getMessage());
        $this->response->setCode($this->getCode());
        $this->response->setDescription($this->getDescription());
        $this->response->setHttpCode($this->getHttpCode());
    }

    public function getMessage()
    {
        return $this->exception->getMessage();
    }

    public function getDescription()
    {
        return class_basename($this->exception).
            ' line '. $this->exception->getLine().
            ' in '. basename($this->exception->getFile());
    }

    public function getHttpCode()
    {
        $httpCode = config('json-exception-handler.http_code');
        if (method_exists($this->exception, 'getStatusCode')) {
            $httpCode = $this->exception->getStatusCode();
        }

        return $httpCode;
    }

    public function getCode($type = 'default')
    {
        $code = $this->exception->getCode();
        if (empty($this->exception->getCode())) {
            $code = config('json-exception-handler.codes.'. $type);
        }

        return $code;
    }

    public function jsonResponse(Exception $exception)
    {
        $this->exception = $exception;
        $this->response = new Response;

        if ($this->exceptionIsTreated()) {
            $this->callExceptionHandler();
        } else {
            $this->setDefaultResponse();
        }

        return response()->json(
            $this->response->toArray(),
            $this->response->getHttpCode()
        );
    }

    /**
     * Check if method to treat exception exists.
     *
     * @param  Exception $exception The exception to be checked
     * @return boolean              If method is callable
     */
    public function exceptionIsTreated()
    {
        return is_callable([$this, $this->methodName()]);
    }

    /**
     * Call the exception handler after of to check if the method exists.
     *
     * @param  Exception $exception
     * @return void                 Call the method
     */
    public function callExceptionHandler()
    {
        $this->{$this->methodName()}($this->exception);
    }

    /**
     * The method name is the exception name with first letter in lower case.
     *
     * @param  Exception $exception
     * @return string               The method name
     */
    public function methodName()
    {
        return lcfirst(class_basename($this->exception));
    }
}
