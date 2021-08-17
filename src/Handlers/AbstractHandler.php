<?php

namespace SMartins\Exceptions\Handlers;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use SMartins\Exceptions\JsonApi\Response as JsonApiResponse;
use SMartins\Exceptions\Response\ErrorHandledCollectionInterface;
use SMartins\Exceptions\Response\ErrorHandledInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
        ValidationException::class => ValidationHandler::class,
        BadRequestHttpException::class => BadRequestHttpHandler::class,
        AuthorizationException::class => AuthorizationHandler::class,
        NotFoundHttpException::class => NotFoundHttpHandler::class,
        'Laravel\Passport\Exceptions\MissingScopeException' => MissingScopeHandler::class,
        'League\OAuth2\Server\Exception\OAuthServerException' => OAuthServerHandler::class,
    ];

    /**
     * Create instance using the Exception to be handled.
     *
     * @param Throwable $e
     */
    public function __construct(Throwable $e)
    {
        $this->exception = $e;
    }

    /**
     * Handle with an exception according to specific definitions. Returns one
     * or more errors using the exception from $exceptions attribute.
     *
     * @return ErrorHandledInterface|ErrorHandledCollectionInterface
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
        if (empty($code = $this->exception->getCode())) {
            return config('json-exception-handler.codes.'.$type);
        }

        return $code;
    }

    /**
     * Return response with handled exception.
     *
     * @return \SMartins\Exceptions\Response\AbstractResponse
     * @throws \SMartins\Exceptions\Response\InvalidContentException
     */
    public function handleException()
    {
        $handler = $this->getExceptionHandler();

        $errors = $this->validatedHandledException($handler->handle());

        $responseHandler = $this->getResponseHandler();

        return new $responseHandler($errors);
    }

    /**
     * Validate response from handle method of handler class.
     *
     * @param ErrorHandledInterface|ErrorHandledCollectionInterface
     * @return ErrorHandledCollectionInterface
     *
     * @throws \SMartins\Exceptions\Response\InvalidContentException
     */
    public function validatedHandledException($error)
    {
        if ($error instanceof ErrorHandledCollectionInterface) {
            return $error->validatedContent(ErrorHandledInterface::class);
        } elseif ($error instanceof ErrorHandledInterface) {
            return $error->toCollection()->setStatusCode($error->getStatus());
        }

        throw new InvalidArgumentException('The errors must be an instance of ['.ErrorHandledInterface::class.'] or ['.ErrorHandledCollectionInterface::class.'].');
    }

    /**
     * Get the class the will handle the Exception from exceptionHandlers attributes.
     *
     * @return mixed
     */
    public function getExceptionHandler()
    {
        $handlers = $this->getConfiguredHandlers();

        $handler = isset($handlers[get_class($this->exception)])
            ? $handlers[get_class($this->exception)]
            : $this->getDefaultHandler();

        return new $handler($this->exception);
    }

    /**
     * Get exception handlers from internal and set on App\Exceptions\Handler.php.
     *
     * @return array
     */
    public function getConfiguredHandlers()
    {
        return array_merge($this->internalExceptionHandlers, $this->exceptionHandlers);
    }

    /**
     * Get default pointer using file and line of exception.
     *
     * @return string
     */
    public function getDefaultPointer()
    {
        return '';
    }

    /**
     * Get default title from exception.
     *
     * @return string
     */
    public function getDefaultTitle()
    {
        return Str::snake(class_basename($this->exception));
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
    public function getDefaultHandler()
    {
        return new Handler($this->exception);
    }

    /**
     * Get default response handler of the if any response handler was defined
     * on config file.
     *
     * @return string
     */
    public function getDefaultResponseHandler()
    {
        return JsonApiResponse::class;
    }

    /**
     * Get the response class that will handle the json response.
     *
     * @todo Check if the response_handler on config is an instance of
     *       \SMartins\Exceptions\Response\AbstractResponse
     * @return string
     */
    public function getResponseHandler()
    {
        $response = config('json-exception-handler.response_handler');

        return $response ?? $this->getDefaultResponseHandler();
    }

    /**
     * Set exception handlers.
     *
     * @param array $handlers
     * @return AbstractHandler
     */
    public function setExceptionHandlers(array $handlers)
    {
        $this->exceptionHandlers = $handlers;

        return $this;
    }
}
