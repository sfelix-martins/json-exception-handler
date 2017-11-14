<?php

namespace SMartins\JsonHandler;

use Illuminate\Support\Facades\App;
use GuzzleHttp\Exception\ClientException;

trait ClientHandler
{
    public function clientException(ClientException $exception)
    {
        $statusCode = 500;
        $title = 'client_exception';
        $code = config($this->configFile.'codes.client.default');
        $detail = __('exception::exceptions.client.unavailable');

        $requestHost = $exception->getRequest()->getUri()->getHost();
        $clientCausers = $this->clientExceptionCausers();

        if ($clientCausers->isPagarme($requestHost)) {
            $code = config('json-exception-handler.codes.client.pagarme') ?? 'pagarme';
        } elseif ($clientCausers->isMailgun($requestHost)) {
            $code = config('json-exception-handler.codes.client.mailgun') ?? 'mailgun';
        }

        if (App::environment('production')) {
            $detail = $detail.' #'.$code;
        } else {
            $response = $exception->getResponse();
            $detail = json_decode($response->getBody())->message;
            $statusCode = $response->getStatusCode();
        }

        $error = [[
            'status'    => $statusCode,
            'code'      => $code,
            'source'    => ['pointer' => $exception->getFile().':'.$exception->getLine()],
            'title'     => $title,
            'detail'    => $detail,
        ]];

        $this->jsonApiResponse->setStatus($statusCode);
        $this->jsonApiResponse->setErrors($error);
    }

    public function clientExceptionCausers()
    {
        return new class() {
            const PAGARME_HOST = 'api.pagar.me';

            const MAILGUN_HOST = 'api.mailgun.net';

            public function isPagarme($host)
            {
                return self::PAGARME_HOST == $host;
            }

            public function isMailgun($host)
            {
                return self::MAILGUN_HOST == $host;
            }
        };
    }
}
