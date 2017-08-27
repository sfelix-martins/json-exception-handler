<?php

namespace SMartins\JsonHandler;

use Exception;

trait JsonHandler
{
    use ValidationHandler;

    public function defaultResponse(Exception $exception)
    {
        return [
            'code' => 1,
            'message' => class_basename($exception),
            'description' => $exception->getMessage().
                ' line '. $exception->getLine().
                ' in '. basename($exception->getFile()),
            'httpCode' => 500
        ];
    }

    public function jsonResponse(array $data)
    {
        $response = ['code' => $data['code'], 'message' => $data['message']];
        if (isset($data['errors'])) {
            $response['errors'] = $data['errors'];
        } elseif (isset($data['description'])) {
            $response['description'] = $data['description'];
        }

        return response()->json($response, $data['httpCode']);
    }
}