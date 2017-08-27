# Json Exception Handler

Add powerful methods to your `App\Exceptions\Handler` to treat json responses.
It is most useful if you is building APIs!

## Using

Install the package 

```console
$ composer require sfelix-martins/json-exception-handler
```

Use the trait on your `App\Exception\Handler`

```php

use SMartins\JsonHandler;

class Handler extends ExceptionHandler
{
    use JsonHandler;

    ...
```

## Example

In your render method you can check if the request expects json and place the following code:

```php
    public function render($request, Exception $exception)
    {
        if ($request->expectsJson()) {
            $response = $this->defaultResponse($exception);

            if ($exception instanceOf ValidationException) {
                $response = $this->validationException($exception);
            }

            return $this->jsonResponse($response);
        }

        return parent::render($request, $exception);
    }
```