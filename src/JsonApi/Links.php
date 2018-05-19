<?php

namespace SMartins\Exceptions\JsonApi;

use SMartins\Exceptions\Traits\NotNullArrayable;

class Links
{
    use NotNullArrayable;

    /**
     * A link that leads to further details about this particular occurrence of
     * the problem.
     *
     * @var string
     */
    protected $about;
}
