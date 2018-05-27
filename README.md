# Laravel Json Exception Handler

[![StyleCI](https://styleci.io/repos/101529653/shield)](https://styleci.io/repos/101529653)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sfelix-martins/json-exception-handler/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sfelix-martins/json-exception-handler/?branch=master)

Adds methods to your `App\Exceptions\Handler` to treat json responses.
It is most useful if you are building APIs!

## JsonAPI

Using [JsonAPI](http://jsonapi.org) standard to  responses!

## Features

Default error response:

```json
{
  "errors": [
    {
      "status": "404",
      "code": "13",
      "title": "model_not_found_exception",
      "detail": "User not found",
      "source": {
        "pointer": "data/id"
      }
    }
  ]
}
```

To `Illuminate\Validation\ValidationException`:

```json
{
  "errors": [
    {
      "status": "422",
      "code": "1411",
      "title": "Required validation failed on field name",
      "detail": "The name field is required.",
      "source": {
        "pointer": "name"
      }
    },
    {
      "status": "422",
      "code": "1421",
      "title": "Email validation failed on field email",
      "detail": "The email must be a valid email address.",
      "source": {
        "pointer": "email"
      }
    }
  ]
}
```

### Treated Exceptions

- `Illuminate\Auth\Access\AuthorizationException`
- `Illuminate\Auth\AuthenticationException`
- `Illuminate\Database\Eloquent\ModelNotFoundException`
- `Illuminate\Validation\ValidationException`
- `Laravel\Passport\Exceptions\MissingScopeException`
- `League\OAuth2\Server\Exception\OAuthServerException`
- `Symfony\Component\HttpKernel\Exception\NotFoundHttpException`
- `Symfony\Component\HttpKernel\Exception\BadRequestHttpException`

## Installing and configuring

Install the package 

```console
$ composer require sfelix-martins/json-exception-handler
```

If you are not using **Laravel 5.5** version add the `JsonHandlerServiceProvider` to your `config/app.php` providers array:

```php
    'providers' => [
        ...
        SMartins\Exceptions\JsonHandlerServiceProvider::class,
    ],
```

Publish the config to set your own exception codes

```sh

$ php artisan vendor:publish --provider="SMartins\JsonHandler\JsonHandlerServiceProvider"
```

Set your exception codes on `config/json-exception-handler.php` on codes array.

You can add more fields and codes to `validation_fields` array.

You can add too your models on lang packages to return the Not Found response translated correctly.

In `resources/lang/vendor/exception/lang/$locale` in `exceptions` file you can set on `models` array. Example:

```php
    'models' => [
        'User' => 'Usuário',
        'Article' => 'Artigo',
    ]
```

## Using

Use the trait on your `App\Exception\Handler` and add method `jsonResponse()` 
passing the `$exception` if `$request` expects a json response on `render()`method

```php

use SMartins\Exceptions\JsonHandler;

class Handler extends ExceptionHandler
{
    use JsonHandler;

    // ...

    public function render($request, Exception $exception)
    {   
        if ($request->expectsJson()) {
            return $this->jsonResponse($exception);
        }

        return parent::render($request, $exception);
    }
    
    // ...
```

### Use sample

```php
class UserController extends Controller
{
    // ...

    public function store(Request $request)
    {
        // Validation
        $request->validate($this->rules);

        // or
        $this->validate($request, $this->rules);

        //and or
        Validator::make($request->all(), $this->rules)->validate();

        if (condition()) {
            // Generate response with http code and message
            abort(403, 'Action forbidden!');
        }

        if (anotherCondition()) {
            // Generate response with message and code
            throw new TokenMismatchException("Error Processing Request", 10);
        }
    }

    public function show($id)
    {
        // If not found the default response is called
        $user = User::findOrFail($id);
        
        // Gate define on AuthServiceProvider
        // Generate an AuthorizationException if fail
        $this->authorize('users.view', $user->id);
    }

```

## Extending

You can too create your own handler to any Exception. E.g.:

- Create a Handler class that extends of `AbstractHandler`:

```php
namespace App\Exceptions;

use GuzzleHttp\Exception\ClientException;
use SMartins\Exceptions\Handlers\AbstractHandler;

class GuzzleClientHandler extends AbstractHandler
{
    /**
     * Create instance using the Exception to be handled.
     *
     * @param \GuzzleHttp\Exception\ClientException $e
     */
    public function __construct(ClientException $e)
    {
        parent::__construct($e);
    }
}
```

- You must implements the method `handle()` from `AbstractHandler` class. The method must return an instance of `Error` or `ErrorCollection`:

```php
namespace App\Exceptions;

use SMartins\Exceptions\JsonAPI\Error;
use SMartins\Exceptions\JsonAPI\Source;
use GuzzleHttp\Exception\ClientException;
use SMartins\Exceptions\Handlers\AbstractHandler;

class GuzzleClientHandler extends AbstractHandler
{
    // ...

    public function handle()
    {
        return (new Error)->setStatus($this->getStatusCode())
            ->setCode($this->getCode())
            ->setSource((new Source())->setPointer($this->getDefaultPointer()))
            ->setTitle($this->getDefaultTitle())
            ->setDetail($this->exception->getMessage());
    }

    public function getCode()
    {
        // You can add a new type of code on `config/json-exception-handlers.php`
        return config('json-exception-handler.codes.client.default');
    }
}
```

```php
namespace App\Exceptions;

use SMartins\Exceptions\JsonAPI\Error;
use SMartins\Exceptions\JsonAPI\Source;
use SMartins\Exceptions\JsonAPI\ErrorCollection;
use SMartins\Exceptions\Handlers\AbstractHandler;

class MyCustomizedHandler extends AbstractHandler
{
    public function __construct(MyCustomizedException $e)
    {
        parent::__construct($e);
    }

    public function handle()
    {
        $errors = (new ErrorCollection)->setStatusCode(400);

        $exceptions = $this->exception->getExceptions();

        foreach ($exceptions as $exception) {
            $error = (new Error)->setStatus(422)
                ->setSource((new Source())->setPointer($this->getDefaultPointer()))
                ->setTitle($this->getDefaultTitle())
                ->setDetail($exception->getMessage());

            $errors->push($error);
        }

        return $errors;
    }
}
```

## Response References:

- http://jsonapi.org/format/#errors
