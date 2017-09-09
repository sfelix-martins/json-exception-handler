<?php

namespace SMartins\JsonHandler;

use Exception;
use SMartins\JsonHandler\Responses\Response;

trait JsonHandler
{
    use ValidationHandler, ModelNotFoundHandler, AuthorizationHandler, NotFoundHttpHandler;

    /**
     * Response instance used to get response.
     *
     * @var Smartins\JsonHandler\Responses\Response
     */
    public $response;

    /**
     * Receive exception instance to be used on methods.
     *
     * @var Exception
     */
    private $exception;

    /**
     * Set the default response on $response attribute. Get default value from
     * methods.
     */
    public function setDefaultResponse()
    {
        $this->response->setMessage($this->getMessage());
        $this->response->setCode($this->getCode());
        $this->response->setDescription($this->getDescription());
        $this->response->setHttpCode($this->getHttpCode());
    }

    /**
     * Get default message from exception.
     *
     * @return string Exception message
     */
    public function getMessage()
    {
        return $this->exception->getMessage();
    }

    /**
     * Mount the description with exception class, line and file.
     *
     * @return string
     */
    public function getDescription()
    {
        return class_basename($this->exception).
            ' line '.$this->exception->getLine().
            ' in '.basename($this->exception->getFile());
    }

    /**
     * Get default http code. Check if exception has getStatusCode() methods.
     * If not get from config file.
     *
     * @return int
     */
    public function getHttpCode()
    {
        $httpCode = config('json-exception-handler.http_code');
        if (method_exists($this->exception, 'getStatusCode')) {
            $httpCode = $this->exception->getStatusCode();
        }

        return $httpCode;
    }

    /**
     * Get error code. If code is empty from config file based on type.
     *
     * @param string $type Code type from config file
     *
     * @return int
     */
    public function getCode($type = 'default')
    {
        $code = $this->exception->getCode();
        if (empty($this->exception->getCode())) {
            $code = config('json-exception-handler.codes.'.$type);
        }

        return $code;
    }

    /**
     * Handle the json response. Check if exception is treated. If true call
     * the specific handler. If false set the default response to be returned.
     *
     * @param Exception $exception
     *
     * @return JsonResponse
     */
    public function jsonResponse(Exception $exception)
    {
        $this->exception = $exception;
        $this->response = new Response();

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
     * @param Exception $exception The exception to be checked
     *
     * @return bool If method is callable
     */
    public function exceptionIsTreated()
    {
        return is_callable([$this, $this->methodName()]);
    }

    /**
     * Call the exception handler after of to check if the method exists.
     *
     * @param Exception $exception
     *
     * @return void Call the method
     */
    public function callExceptionHandler()
    {
        $this->{$this->methodName()}($this->exception);
    }

    /**
     * The method name is the exception name with first letter in lower case.
     *
     * @param Exception $exception
     *
     * @return string The method name
     */
    public function methodName()
    {
        return lcfirst(class_basename($this->exception));
    }
}
