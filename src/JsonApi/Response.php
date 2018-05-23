<?php

namespace SMartins\Exceptions\JsonApi;

use Illuminate\Http\JsonResponse;
use SMartins\Exceptions\Response\AbstractResponse;

class Response extends AbstractResponse
{
    /**
     * Returns JSON response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function json(): JsonResponse
    {
        return new JsonResponse(
            ['errors' => $this->getErrors()->toArray()],
            $this->getStatus(),
            $this->getErrors()->getHeaders()
        );
    }
}
