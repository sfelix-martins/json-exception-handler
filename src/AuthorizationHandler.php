<?php

namespace SMartins\JsonHandler;

use Illuminate\Auth\Access\AuthorizationException;

trait AuthorizationHandler
{
    public function authorizationException(AuthorizationException $exception)
    {
        $this->response->setMessage($exception->getMessage());
        $this->response->setCode($this->getCode('authorization'));
        $this->response->setHttpCode(403);
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
