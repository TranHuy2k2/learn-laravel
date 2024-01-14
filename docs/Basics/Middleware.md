---
weight: 2
---

# Middlewares

Middleware provide a convenient mechanism for inspecting and filtering HTTP requests entering your application.

Additional middleware can be written to perform a variety of tasks besides authentication. For example, a logging middleware might log all incoming requests to your application.

## Defining Middleware

Use the command `make:middleware` to create a new middleware class:

```bash
php artisan make:middleware EnsureTokenIsValid
```

This will generate a middleware class in the `app/Http/Middleware` directory.

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->input('token') !== 'my-secret-token') {
            return redirect('home');
        }

        return $next($request);
    }
}
```

## Middleware and Responses

A middleware can intercept both the request and the response.

For example, to perform some task before the application handles the incoming request:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BeforeMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Perform action

        return $next($request);
    }
}
```

To perform some task after the application handles the incoming request:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AfterMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Perform action

        return $response;
    }
}
```

## Registering Middleware

### Global Middleware

To register a global middleware, use the `$middleware` property of your `app/Http/Kernel.php` class.

```php
protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];
```

### Assigning Middleware To Routes

If you would like to assign middleware to specific routes, you may invoke the middleware method when defining the route:

```php
Route::get('/', function () {
    // ...
})->middleware([First::class, Second::class]);
```

If you feel like that is too long and complicated, you can use the `$middlewareAliases` in the `app/Http/Kernel.php` class to define aliases for your middleware. After that, you can use the alias when defining the route:

```php
Route::get('/profile', function () {
    // ...
})->middleware('auth');
```
