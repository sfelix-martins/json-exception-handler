<?php

namespace SMartins\Exceptions\Tests\Unit;

use InvalidArgumentException;
use SMartins\Exceptions\JsonApi\Error;
use SMartins\Exceptions\Tests\TestCase;
use SMartins\Exceptions\Handlers\Handler;
use SMartins\Exceptions\JsonApi\ErrorCollection;

class AbstractHandlerTest extends TestCase
{
    public function testShouldReturnsTheHandlerClassOnGetExceptionHandler()
    {
        $handler = new Handler(new \Exception);

        $this->assertInstanceOf(Handler::class, $handler->getExceptionHandler());
    }

    public function testGetDefaultHandler()
    {
        $handler = new Handler(new \Exception);

        $this->assertInstanceOf(Handler::class, $handler->getDefaultHandler());
    }

    public function testValidateHandledExceptionWithInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);

        $handler = new Handler(new \Exception);
        $handler->validatedHandledException('invalid');
    }
}
