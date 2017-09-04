<?php

namespace SMartins\JsonHandler;

use SMartins\JsonHandler\Responses\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait NotFoundHttpHandler
{
    public function notFoundHttpException(NotFoundHttpException $exception)
    {
        $this->response->setMessage($this->getMessage($exception));
        $this->response->setCode($this->getCode('not_found_http'));
        $this->response->setDescription($this->getDescription($exception));
        $this->response->setHttpCode($exception->getStatusCode());
    }

    public function getMessage($exception)
    {
        $message = !empty($exception->getMessage()) ? $exception->getMessage() : class_basename($exception);
        if (basename($exception->getFile()) === 'RouteCollection.php') {
            $message = 'Route not found.';
        }

        return $message;
    }
}
