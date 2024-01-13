---
weight: 1
---

# Request Life Cycle

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

One of the most important kernel bootstrapping actions is loading the service providers for your application.

**Service Workers** are responsible for bootstrapping all of the framework's various components, such as the database, queue, validation, and routing components.

They are configured in your `config/app.php` configuration file's `providers` array.

```php
'providers' => ServiceProvider::defaultProviders()->merge([
        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
    ])->toArray(),
```

Laravel will iterate through this list of providers and instantiate each of them. After instantiating the providers, the `register` method will be called on all of the providers.

Then, once all of the providers have been registered, the `boot` method will be called on each provider.

> Essentially every major feature offered by Laravel is bootstrapped and configured by a service provider

### Routing

One of the most important service providers in your application is the `App\Providers\RouteServiceProvider`. This service provider loads the route files contained within your application's `routes` directory.

Here is the boot method of the RouteServiceProvider:

```php
    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // 'api' and 'web' are the names of the middleware groups
        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
```

Middleware provide a convenient mechanism for filtering or examining HTTP requests entering your application.

Some middleware are assigned to all routes within the application, like those defined in the `$middleware` property of your HTTP kernel, while some are only assigned to specific routes or route groups.

If the request passes through all of the matched route's assigned middleware, the route or controller method will be executed and the response returned by the route or controller method **will be sent back through the route's chain of middleware**.

Finally, once the response travels back through the middleware, the HTTP kernel's `handle` method returns the response object and the index.php file calls the `send` method on the returned response. The send method sends the response content to the user's web browser. We've finished our journey through the entire Laravel request lifecycle!

> Middleware -> Route -> Controller -> Middleware -> Http Kernel's handle method -> index.php 's send method -> Response
