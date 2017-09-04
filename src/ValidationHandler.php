<?php

namespace SMartins\JsonHandler;

use Illuminate\Validation\ValidationException;
use SMartins\JsonHandler\Responses\Response;

trait ValidationHandler
{
    public function validationException(ValidationException $exception)
    {
        $this->response->setMessage('The given data failed to pass validation.');
        $this->response->setCode($this->getCode('validation'));
        $this->response->setErrors($this->formattedErrors($exception));
        $this->response->setHttpCode(422);
    }

    public function formattedErrors(ValidationException $exception)
    {
        return $this->formatErrorMessages($this->getTreatedMessages($exception));
    }

    public function getTreatedMessages($exception)
    {
        $messages = [];
        if ($exception->response) {
            $messages = $this->getMessagesFromJsonResponse($exception);
        } else {
            $messages = $this->getMessagesFromValidator($exception);
        }

        return $messages;
    }

    public function getMessagesFromJsonResponse($exception)
    {
        return $exception->response->original;
    }

    public function getMessagesFromValidator($exception)
    {
        return $exception->validator->messages()->messages();
    }

    public function formatErrorMessages($messages)
    {
        $errors = [];
        foreach ($messages as $field => $message) {
            $error = [
                'code' => config('json-exception-handler.codes.validation_fields.'.$field),
                'field' => $field,
                'message' => $message
            ];

            array_push($errors, $error);
        }

        return $errors;
    }
}
