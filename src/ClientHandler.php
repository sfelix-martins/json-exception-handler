<?php

namespace SMartins\JsonHandler;

use GuzzleHttp\Exception\ClientException;

trait ClientHandler
{
    public function clientException(ClientException $exception)
    {
        $requestHost = $exception->getRequest()->getUri()->getHost();

        $detail = __('exception::exceptions.client.unavailable');
        $code = $this->getCode('client.default');
        if ($this->clientExceptionCausers()->isPagarme($requestHost)) {
            $pagarMeCode = config($this->configFile.'codes.client.pagarme') ?? $code;

            $detail = $detail.' #'.$pagarMeCode;
            $code = $pagarMeCode;
        }

        $error = [[
            'status'    => 500,
            'code'      => $code,
            'source'    => ['pointer' => $exception->getFile().':'.$exception->getLine()],
            'title'     => 'client_exception',
            'detail'    => $detail,
        ]];

        $this->jsonApiResponse->setStatus(500);
        $this->jsonApiResponse->setErrors($error);
    }

    public function clientExceptionCausers()
    {
        return new class() {
            const PAGARME_HOST = 'api.pagar.me';

            public function isPagarme($host)
            {
                return self::PAGARME_HOST == $host;
            }
        };
    }
}
