<?php

namespace SMartins\Exceptions\Tests\Unit;

use SMartins\Exceptions\Tests\TestCase;
use SMartins\Exceptions\Tests\GettersAndSetters;
use SMartins\Exceptions\JsonApi\ErrorCollection;

class ErrorCollectionTest extends TestCase
{
    public function testSetHeaders()
    {
        $error = new ErrorCollection;
        $this->assertInstanceOf(ErrorCollection::class, $error->setHeaders(['foo' => 'bar']));

        $this->assertEquals($error->getHeaders(), ['foo' => 'bar']);
    }
}
