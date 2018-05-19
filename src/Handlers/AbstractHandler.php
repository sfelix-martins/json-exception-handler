<?php

namespace SMartins\Exceptions\Handlers;

use Exception;
use RuntimeException;
use SMartins\Exceptions\JsonApi\Response;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Laravel\Passport\Exceptions\MissingScopeException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class AbstractHandler
{
    /**
     * The exception thrown.
     *
     * @var \Exception
     */
    protected $exception;

    /**
     * An array where the key is the class exception and the value is the handler
     * class that will treat the exception.
     *
     * @var array
     */
    protected $exceptionHandlers = [];

    /**
     * An internal array where the key is the exception class and the value is
     * the handler class that will treat the exception.
     *
     * @var array
     */
    protected $internalExceptionHandlers = [
        Exception::class                => Handler::class,
        ModelNotFoundException::class   => ModelNotFoundHandler::class,
        AuthenticationException::class  => AuthenticationHandler::class,
        AuthorizationException::class   => AuthorizationHandler::class,
        AuthorizationException::class   => AuthorizationHandler::class,
        ValidationException::class      => ValidationHandler::class,
        BadRequestHttpException::class  => BadRequestHttpHandler::class,
        NotFoundHttpException::class    => NotFoundHttpHandler::class,
        MissingScopeException::class    => MissingScopeHandler::class,
        OAuthServerException::class     => OAuthServerHandler::class,
    ];

    public function __construct(Exception $e)
    {
        $this->exception = $e;
    }

    /**
     * Handle with an exception according to specific definitions. Returns one
     * or more errors using the exception from $exceptions attribute.
     *
     * @return array|\Illuminate\Support\Collection|
     *         \Smartins\Exceptions\JsonApi\ErrorCollection|
     *         \SMartins\Exceptions\JsonApi\Error
     */
    abstract public function handle();

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
            $code = config('json-exception-handler.codes.'.$type);
        }

        return $code;
    }

    /**
     * Return response with handled exception.
     *
     * @return \SMartins\Exceptions\JsonApi\Response
     */
    public function handleException()
    {
        $handler = $this->getExceptionHandler();

        $error = $handler->handle();

        return new Response($error);
    }

    /**
     * Get the class the will handle the Exception from exceptionHandlers attributes.
     *
     * @return mixed
     */
    public function getExceptionHandler()
    {
        $handlers = array_merge($this->exceptionHandlers, $this->internalExceptionHandlers);

        $handler = isset($handlers[get_class($this->exception)])
            ? $handlers[get_class($this->exception)]
            : $this->defaultHandler();

        return new $handler($this->exception);
    }

    /**
     * Get default pointer using file and line of exception.
     *
     * @return string
     */
    public function getDefaultPointer()
    {
        return $this->exception->getFile().':'.$this->exception->getLine();
    }

    /**
     * Get default title from exception.
     *
     * @return string
     */
    public function getDefaultTitle()
    {
        return snake_case(class_basename($this->exception));
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
            return $this->exception->getStatusCode();
        }

        return config('json-exception-handler.http_code');
    }

    /**
     * The default handler to handle not treated exceptions.
     *
     * @return \SMartins\Exceptions\Handlers\Handler
     */
    public function defaultHandler()
    {
        return new Handler($this->exception);
    }
}
