<?php

namespace SMartins\Exceptions\Response;

use Illuminate\Contracts\Support\Arrayable;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

interface ErrorCollectionInterface extends Arrayable, HttpExceptionInterface
{
    //
}
