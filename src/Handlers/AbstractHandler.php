<?php

namespace SMartins\Exceptions\Handlers;

use Exception;
use RuntimeException;
use InvalidArgumentException;
use Illuminate\Support\Collection;
use SMartins\Exceptions\JsonApi\Error;
use SMartins\Exceptions\JsonApi\Response;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use SMartins\Exceptions\JsonApi\ErrorCollection;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        Exception::class => Handler::class,
        ModelNotFoundException::class => ModelNotFoundHandler::class,
        AuthenticationException::class => AuthenticationHandler::class,
        AuthorizationException::class => AuthorizationHandler::class,
        AuthorizationException::class => AuthorizationHandler::class,
        ValidationException::class => ValidationHandler::class,
        BadRequestHttpException::class => BadRequestHttpHandler::class,
        NotFoundHttpException::class => NotFoundHttpHandler::class,
        'Laravel\Passport\Exceptions\MissingScopeException' => MissingScopeHandler::class,
        'League\OAuth2\Server\Exception\OAuthServerException' => OAuthServerHandler::class,
    ];

    /**
     * Create instance using the Exception to be handled.
     *
     * @param Exception $e
     */
    public function __construct(Exception $e)
    {
        $this->exception = $e;
    }

    /**
     * Handle with an exception according to specific definitions. Returns one
     * or more errors using the exception from $exceptions attribute.
     *
     * @return \SMartins\Exceptions\JsonApi\Error|\Smartins\Exceptions\JsonApi\ErrorCollection|array|\Illuminate\Support\Collection
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

        $errors = $this->validatedHandledException($handler->handle());

        return new Response($errors);
    }

    /**
     * Validate response from handle method of handler class.
     *
     * @param  mixed $errors
     * @return \SMartins\Exceptions\JsonApi\ErrorCollection
     *
     * @throws \InvalidArgumentException
     */
    public function validatedHandledException($errors)
    {
        if (is_array($errors) ||
            (is_object($errors) && get_class($errors) === Collection::class)
        ) {
            $errors = new ErrorCollection($errors);
        } elseif ($errors instanceof Error) {
            $errors = (new ErrorCollection)->push($errors)->setStatusCode($errors->getStatus());
        }

        if (! $errors instanceof ErrorCollection) {
            throw new InvalidArgumentException('The errors must be an array, ['.Collection::class.'], ['.Error::class.'] or ['.ErrorCollection::class.'].');
        }

        return $errors->validated();
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
