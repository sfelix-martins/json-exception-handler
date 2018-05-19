<?php

namespace SMartins\Exceptions\Tests\Unit;

use SMartins\Exceptions\Tests\TestCase;
use SMartins\Exceptions\Handlers\Handler;

class AbstractHandlerTest extends TestCase
{
    public function testShouldReturnsTheHandlerClassOnGetExceptionHandler()
    {
        $handler = new Handler(new \Exception);

        $this->assertInstanceOf(Handler::class, $handler->getExceptionHandler());
    }
}
