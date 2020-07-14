<?php

namespace SMartins\Exceptions;

use SMartins\Exceptions\Handlers\Handler;
use Throwable;

trait JsonHandler
{
    /**
     * Handle the json response. Check if exception is treated. If true call
     * the specific handler. If false set the default response to be returned.
     *
     * @param  \Throwable  $exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function jsonResponse(Throwable $exception)
    {
        $handler = new Handler($exception);

        if (property_exists($this, 'exceptionHandlers')) {
            $handler->setExceptionHandlers($this->exceptionHandlers);
        }

        return $handler->handleException()->json();
    }
}
