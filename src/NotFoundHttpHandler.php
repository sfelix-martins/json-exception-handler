<?php

namespace SMartins\JsonHandler;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait NotFoundHttpHandler
{
    /**
     * Set response parameters to NotFoundHttpException.
     *
     * @param  NotFoundHttpException $exception
     */
    public function notFoundHttpException(NotFoundHttpException $exception)
    {
        $statuCode = $exception->getStatusCode();
        $error = [[
            'status'    => $statuCode,
            'code'      => $this->getCode('not_found_http'),
            'source'    => ['pointer' => ''],
            'title'     => $this->getNotFoundMessage($exception),
            'detail'    => $this->getDescription($exception),
        ]];

        $this->jsonApiResponse->setStatus($statuCode);
        $this->jsonApiResponse->setErrors($error);
    }

    /**
     * Get message based on file. If file is RouteCollection return specific
     * message.
     *
     * @param  NotFoundHttpException $exception
     * @return string
     */
    public function getNotFoundMessage(NotFoundHttpException $exception)
    {
        $message = ! empty($exception->getMessage()) ? $exception->getMessage() : class_basename($exception);
        if (basename($exception->getFile()) === 'RouteCollection.php') {
            $message = 'Route not found.';
        }

        return $message;
    }
}
