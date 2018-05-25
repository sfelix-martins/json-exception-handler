<?php

namespace SMartins\Exceptions\Tests\Unit;

use SMartins\Exceptions\JsonApi\Error;
use SMartins\Exceptions\JsonApi\Links;
use SMartins\Exceptions\Tests\TestCase;
use SMartins\Exceptions\JsonApi\Source;
use SMartins\Exceptions\JsonApi\ErrorCollection;
use SMartins\Exceptions\Tests\GettersAndSetters;

class ErrorTest extends TestCase
{
    public function testGetAndSetId()
    {
        $error = new Error;
        $this->assertInstanceOf(Error::class, $error->setId(1));
        $this->assertEquals(1, $error->getId());
    }

    public function testGetAndSetLinks()
    {
        $error = new Error;
        $this->assertInstanceOf(Error::class, $error->setLinks($links = new Links));
        $this->assertEquals($links, $error->getLinks());
    }

    public function testGetAndSetCode()
    {
        $error = new Error;
        $this->assertInstanceOf(Error::class, $error->setCode(1));
        $this->assertEquals(1, $error->getCode());
    }

    public function testGetAndSetTitle()
    {
        $error = new Error;
        $this->assertInstanceOf(Error::class, $error->setTitle('tests'));
        $this->assertEquals('tests', $error->getTitle());
    }

    public function testGetAndSetDetail()
    {
        $error = new Error;
        $this->assertInstanceOf(Error::class, $error->setDetail('detail'));
        $this->assertEquals('detail', $error->getDetail());
    }

    public function testGetAndSerSource()
    {
        $error = new Error;
        $this->assertInstanceOf(Error::class, $error->setSource($source = new Source));
        $this->assertEquals($source, $error->getSource());
    }

    public function testToCollection()
    {
        $error = new Error;
        $this->assertInstanceOf(ErrorCollection::class, $error->toCollection());
    }
}
