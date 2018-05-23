<?php

namespace SMartins\Exceptions\Tests\Fixtures\Exceptions;

use Exception;
use SMartins\Exceptions\JsonHandler;
use Illuminate\Auth\Access\AuthorizationException;
use SMartins\Exceptions\Handlers\NotFoundHttpHandler;
use SMartins\Exceptions\Handlers\AuthorizationHandler;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    use JsonHandler;

    /**
     * An array where the key is the class exception and the value is the handler
     * class that will treat the exception.
     *
     * @var array
     */
    protected $exceptionHandlers = [
        AuthorizationException::class => AuthorizationHandler::class,
        NotFoundHttpException::class => NotFoundHttpHandler::class,
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($request->expectsJson()) {
            return $this->jsonResponse($exception);
        }

        return parent::render($request, $exception);
    }
}
