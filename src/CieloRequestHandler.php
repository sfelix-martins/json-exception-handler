<?php

namespace SMartins\JsonHandler;

use Cielo\API30\Ecommerce\Request\CieloRequestException;

trait CieloRequestHandler
{
    public function cieloRequestException(CieloRequestException $e)
    {
        $errors = [];
        $code = $e->getCode();
        do {
            $cieloError = $e->getCieloError();
            $error = [
                'status'    => $e->getCode(),
                'code'      => $this->getCode('cielo').$cieloError->getCode(),
                'source'    => ['pointer' => $e->getFile().':'.$e->getLine()],
                'title'     => $e->getMessage(),
                'detail'    => $cieloError->getMessage(),
            ];
            if (! in_array($error, $errors)) {
                array_push($errors, $error);
            }
            $e = $e->getPrevious();
        } while (method_exists($e, 'getPrevious'));

        $this->jsonApiResponse->setStatus($code);
        $this->jsonApiResponse->setErrors($errors);
    }
}
