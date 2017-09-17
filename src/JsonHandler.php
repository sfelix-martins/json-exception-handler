<?php

namespace SMartins\JsonHandler;

use Exception;
use SMartins\JsonHandler\Responses\JsonApiResponse;

trait JsonHandler
{
    use ValidationHandler, ModelNotFoundHandler, AuthorizationHandler, NotFoundHttpHandler;

    /**
     * Config file name.
     * @var string
     */
    public $configFile = 'json-exception-handler';

    /**
     * JsonApiResponse instance used on another traits to set response.
     * @var SMartins\JsonHandler\Responses\JsonApiResponse;
     */
    public $jsonApiResponse;

    /**
     * Receive exception instance to be used on methods.
     * @var Exception
     */
    private $exception;

    /**
     * Set the default response on $response attribute. Get default value from
     * methods.
     */
    public function setDefaultResponse()
    {
        $error = [[
            'status'    => $this->getStatusCode(),
            'code'      => $this->getCode(),
            'source'    => ['pointer' => ''],
            'title'     => $this->getMessage(),
            'detail'    => $this->getDescription(),
        ]];

        $this->jsonApiResponse->setStatus($this->getStatusCode());
        $this->jsonApiResponse->setErrors($error);
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
    public function getStatusCode()
    {
        if (method_exists($this->exception, 'getStatusCode')) {
            $httpCode = $this->exception->getStatusCode();
        } else {
            $httpCode = config($this->configFile.'.http_code');
        }

        return $httpCode;
    }

    /**
     * Get error code. If code is empty from config file based on type.
     *
     * @param  string $type Code type from config file
     * @return int
     */
    public function getCode($type = 'default')
    {
        $code = $this->exception->getCode();
        if (empty($this->exception->getCode())) {
            $code = config($this->configFile.'.codes.'.$type);
        }

        return $code;
    }

    /**
     * Handle the json response. Check if exception is treated. If true call
     * the specific handler. If false set the default response to be returned.
     *
     * @param  Exception $exception
     * @return JsonResponse
     */
    public function jsonResponse(Exception $exception)
    {
        $this->exception = $exception;
        $this->jsonApiResponse = new JsonApiResponse;

        if ($this->exceptionIsTreated()) {
            $this->callExceptionHandler();
        } else {
            $this->setDefaultResponse();
        }

        return response()->json(
            $this->jsonApiResponse->toArray(),
            $this->jsonApiResponse->getStatus()
        );
    }

    /**
     * Check if method to treat exception exists.
     *
     * @param  Exception $exception The exception to be checked
     * @return bool              If method is callable
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
