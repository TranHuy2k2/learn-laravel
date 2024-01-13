# Learning Laravel

For full documentation visit [Laravel](https://laravel.com/).

## Life Cycle Overview

### Entry Point

The entry point for all requests to a Laravel application is the `public/index.php` file. All requests are directed to this file by your web server (Apache / Nginx) configuration. The index.php file doesn't contain much code. Rather, `it is a starting point for loading the rest of the framework`.

The `index.php` file loads the Composer generated autoloader definition, and then retrieves an instance of the Laravel application from `bootstrap/app.php`. The first action taken by Laravel itself is to create an instance of the application / service container.

### HTTP / Console Kernels

Next, the incoming request is sent to either the `HTTP kernel` or the `console kernel`, depending on the type of request that is entering the application. These two kernels serve as the central location that all requests flow through. For now, let's just focus on the HTTP kernel, which is located in `app/Http/Kernel.php`.

> Kernel is a central location that all requests flow through.

#### HTTP Kernel

Http Kernel is the class that extends `Illuminate\Foundation\Http\Kernel` class. In here, it defines the `bootstrappers` and `middleware` that will be applied before the request is executed.

These bootstrappers can be:

-   **Configure Error Handling**
-   **Configure Logging**
-   **Configure Environment**
-   **Handle Exceptions**
-   And many other tasks that need to be done before the request is executed.

The HTTP kernel also defines a list of HTTP middleware that all requests must pass through before being handled by the application. By default, these are:

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

The middleware could be:

-   Read & Write Session
-   Verify CSRF Token
-   Determine whether the application is in maintenance mode

The method signature for the HTTP kernel's handle method is quite simple: it receives a `Request` and returns a `Response`. Think of the kernel as being a big black box that represents your entire application. Feed it HTTP requests and it will return HTTP responses.

> Give the HTTP kernel a request and it will return a response.

### Service Providers
