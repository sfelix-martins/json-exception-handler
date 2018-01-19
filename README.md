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
            "status": 404,
            "code": 15,
            "source": {
                "pointer": ""
            },
            "title": "Route not found.",
            "detail": "NotFoundHttpException line 179 in RouteCollection.php"
        }
    ]
}
```

To `Illuminate\Validation\ValidationException`:

```json
{
    "errors": [
        {
            "status": 422,
            "code": 1411,
            "source": {
                "parameter": "name"
            },
            "title": "Required validation failed on field name",
            "detail": "The name field is required."
        },
        {
            "status": 422,
            "code": 1433,
            "source": {
                "parameter": "password"
            },
            "title": "Min validation failed on field password",
            "detail": "The password must be at least 6 characters."
        },
        {
            "status": 422,
            "code": 1432,
            "source": {
                "parameter": "password"
            },
            "title": "Confirmed validation failed on field password",
            "detail": "The password confirmation does not match."
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
- `GuzzleHttp\Exception\ClientException`
- `Cielo\API30\Ecommerce\Request\CieloRequestException`

## Installing and configuring

Install the package 

```console
$ composer require sfelix-martins/json-exception-handler
```

If you are not using **Laravel 5.5** version add the `JsonHandlerServiceProvider` to your `config/app.php` providers array:

```php
    'providers' => [
        ...
        SMartins\JsonHandler\JsonHandlerServiceProvider::class,
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

use SMartins\JsonHandler\JsonHandler;

class Handler extends ExceptionHandler
{
    use JsonHandler;

    ...

    public function render($request, Exception $exception)
    {   
        if ($request->expectsJson()) {
            return $this->jsonResponse($exception);
        }

        return parent::render($request, $exception);
    }
    
    ...
```

### Use sample

```php

class UserController extends Controller
{
    ...

    public function store(Request $request)
    {
        // Validation
        $request->validate($this->rules); // on Laravel 5.5

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

## Response References:

- http://jsonapi.org/format/#errors
