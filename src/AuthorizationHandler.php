<?php

namespace SMartins\JsonHandler;

use Illuminate\Auth\Access\AuthorizationException;

trait AuthorizationHandler
{
    public function authorizationException(AuthorizationException $exception)
    {
        $error = [[
            'status'    => 403,
            'code'      => $this->getCode('authorization'),
            'source'    => ['pointer' => ''],
            'title'     => 'Action not allowed.',
            'detail'    => $exception->getMessage(),
        ]];

        $this->jsonApiResponse->setStatus(403);
        $this->jsonApiResponse->setErrors($error);
    }

    public function generateDescription($traces)
    {
        $action = '';
        foreach ($traces as $trace) {
            if ($trace['function'] === 'authorize') {
                $action = $this->extractAction($trace['args']);
                break;
            }
        }
    }

    public function extractAction($args)
    {
        $action = reset($args);

        $this->getWord($action);
    }

    public function getWords($action)
    {
        $words = explode('.', $action);
        if (!(count($words) > 1)) {
            $words = explode('-', $action);
            if (!(count($words) > 1)) {
                $words = preg_split('/(?=[A-Z])/', $action);
            }
        }
    }
}
