<?php

namespace SMartins\Exceptions\Tests\Unit;

use Exception;
use SMartins\Exceptions\JsonApi\Error;
use SMartins\Exceptions\Tests\TestCase;
use SMartins\Exceptions\Handlers\Handler;

class HandlerTest extends TestCase
{
    public function testHandle()
    {
        $exception = new Exception('Test exception', 1);

        $handler = new Handler($exception);
        $this->assertInstanceOf(Error::class, $handler->handle());
    }
}
