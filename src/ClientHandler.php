<?php

namespace SMartins\JsonHandler;

use Illuminate\Support\Facades\App;
use GuzzleHttp\Exception\ClientException;

trait ClientHandler
{
    public function clientException($exception)
    {
        $statusCode = 500;
        $title = 'client_exception';
        $detail = $exception->getMessage();
        $code = $this->getCode();

        if ($exception instanceof ClientException) {
            $requestHost = $exception->getRequest()->getUri()->getHost();
            $clientCausers = $this->clientExceptionCausers();

            $response = $exception->getResponse();

            if ($clientCausers->isPagarme($requestHost)) {
                $code = config('json-exception-handler.codes.client.pagarme') ?? 'pagarme';
                $errors = json_decode($response->getBody())->errors;

                $firstErrorMessage = '';
                foreach ($errors as $error) {
                    $firstErrorMessage = $error->message;
                    break;
                }

                $detailedError = $firstErrorMessage.' #'.$code;
            } elseif ($clientCausers->isMailgun($requestHost)) {
                $code = config('json-exception-handler.codes.client.mailgun') ?? 'mailgun';
                $detailedError = json_decode($response->getBody())->message.' #'.$code;
            } else {
                // Unknown error
                $code = config('json-exception-handler.codes.client.default');
            }

            if (App::environment('production')) {
                $detail = __('exception::exceptions.client.unavailable').' #'.$code;
            } else {
                // Return more details about error
                $detail = $detailedError ?? $detail;
                $statusCode = $response->getStatusCode();
            }
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
