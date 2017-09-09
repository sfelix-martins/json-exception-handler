# Laravel Json Exception Handler

[![StyleCI](https://styleci.io/repos/101529653/shield)](https://styleci.io/repos/101529653)

Adds methods to your `App\Exceptions\Handler` to treat json responses.
It is most useful if you are building APIs!

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

### Treated Exceptions

- `Illuminate\Validation\ValidationException`
- `Illuminate\Database\Eloquent\ModelNotFoundException`
- `Illuminate\Auth\Access\AuthorizationException`

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
        // If validate fails
        $this->validate($request, $rules);

        //or
        Validator::make($request->all(), $rules)->validate();

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

- http://www.vinaysahni.com/best-practices-for-a-pragmatic-restful-api