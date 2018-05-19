<?php

namespace SMartins\Exceptions;

use Exception;
use SMartins\Exceptions\Handlers\Handler;

trait JsonHandler
{
    /**
     * Handle the json response. Check if exception is treated. If true call
     * the specific handler. If false set the default response to be returned.
     *
     * @param  Exception $exception
     * @return JsonResponse
     */
    public function jsonResponse(Exception $exception)
    {
        return (new Handler($exception))->handleException()->json();
    }
}
