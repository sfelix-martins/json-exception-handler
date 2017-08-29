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

### Treated Exceptions

- Illuminate\Validation\ValidationException;
- Illuminate\Database\Eloquent\ModelNotFoundException;

## Using

Install the package 

```console
$ composer require sfelix-martins/json-exception-handler
```

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

use App\User;
use Validator;

class UserController extends Controller
{
    ...

    public function store(Request $request)
    {
        // If validate fails
        $this->validate($request, $this->rules);

        //or
        Validator::make($request->all(), $this->rules)->validate();
    }

    public function show($id)
    {
        // If not found the default response is called
        $user = User::findOrFail($id);
    }

```