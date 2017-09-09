<?php

namespace SMartins\JsonHandler;

use SMartins\JsonHandler\Responses\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait NotFoundHttpHandler
{
    /**
     * Set response parameters to NotFoundHttpException.
     *
     * @param NotFoundHttpException $exception
     */
    public function notFoundHttpException(NotFoundHttpException $exception)
    {
        $this->response->setMessage($this->getNotFoundMessage($exception));
        $this->response->setCode($this->getCode('not_found_http'));
        $this->response->setDescription($this->getDescription($exception));
        $this->response->setHttpCode($exception->getStatusCode());
    }

    /**
     * Get message based on file. If file is RouteCollection return specific
     * message.
     *
     * @param NotFoundHttpException $exception
     *
     * @return string
     */
    public function getNotFoundMessage(NotFoundHttpException $exception)
    {
        $message = !empty($exception->getMessage()) ? $exception->getMessage() : class_basename($exception);
        if (basename($exception->getFile()) === 'RouteCollection.php') {
            $message = 'Route not found.';
        }

        return $message;
    }
}
