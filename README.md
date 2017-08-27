# Laravel Json Exception Handler

Add methods to your `App\Exceptions\Handler` to treat json responses.
It is most useful if you is building APIs!

## Features

Default error response:

```json
{
    "code" : 1234,
    "message" : "Something bad happened :(",
    "description" : "More details about the error here"
}
```

To `Illuminate\Validation\ValidationException`:

```json
{
    "code": 123,
    "message": "The given data failed to pass validation.",
    "errors": [
        {
            "code": 1,
            "field": "name",
            "message": [
                "The name field is required."
            ]
        },
        {
            "code": 2,
            "field": "email",
            "message": [
                "The email has already been taken."
            ]
        }
    ]
}
```

### Available methods

- defaultResponse($exception): Return the default error response
- validationException($exception): Return the validation error response
- jsonResponse($response): Return the json response

## Using

Install the package 

```console
$ composer require sfelix-martins/json-exception-handler
```

Use the trait on your `App\Exception\Handler`

```php

use SMartins\JsonHandler\JsonHandler;

class Handler extends ExceptionHandler
{
    use JsonHandler;

    ...
```

### Example

In your render method you can check if the request expects json and place the following code:

```php
    public function render($request, Exception $exception)
    {
        if ($request->expectsJson()) {
            // Get default response
            $response = $this->defaultResponse($exception);

            if ($exception instanceOf ValidationException) {
                // Get validation exception response
                $response = $this->validationException($exception);
            }
    
            //
            return $this->jsonResponse($response);
        }

        return parent::render($request, $exception);
    }
```