<?php

namespace SMartins\JsonHandler;

use Illuminate\Validation\ValidationException;
use SMartins\JsonHandler\Responses\Response;

trait ValidationHandler
{
    public function validationException(ValidationException $exception)
    {
        $response = new Response;
        $response->setMessage('The given data failed to pass validation.');
        $response->setCode(config('json-exception-handler.codes.validation'));
        $response->setErrors($this->formattedErrors($exception));
        $response->setHttpCode(422);

        return $this->response = $response;
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
