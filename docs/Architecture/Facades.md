---
weight: 4
---

# Facades

Facades provide a static interface to classes that are available in the application's service container.

Laravel facades serve as a `static proxies` to underlying classes in the service container, providing the benefit of a terse, expressive syntax while maintaining more testability and flexibility than traditional static methods.

All of Laravel's facades are defined in the `Illuminate\Support\Facades` namespace. So, we can easily access a facade like so:

```php
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

Route::get('/cache', function () {
    return Cache::get('key');
});
```

## Helper function

To complement **Facades**, Laravel offers a variety of global "helper functions" that make it even easier to interact with common Laravel features.

Some of the common helper functions you may interact with are `view`, `response`, `url`, `config`, and more.

## How Facade works

`Facade` is a class that provide access to an object from the container. Laravel's facades, and any custom facades you create, will extend the base `Illuminate\Support\Facades\Facade` class.

'Facade' base class of Laravel makes use of the `__callStatic` magic-method to defer calls from your facade to an object resolved from the container.

For example, the following code call the `get` method on the `Cache` facade:

```php
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

# Import the Cache Facade
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Show the profile for the given user.
     */
    public function showProfile(string $id): View
    {
        $user = Cache::get('user:'.$id);

        return view('profile', ['user' => $user]);
    }
}
```

This `Cache` facade serves as a proxy for accessing the underlying implementation of the Illuminate\Contracts\Cache\Factory interface. Any call made to the `Cache` facade will be passed to the underlying instance of the `Cache` binding in the service container.

Looking at that `Illuminate\Support\Facades\Cache` class, you'll see that there is no static method get:

```php
class Cache extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'cache';
    }
}
```

Instead, it extends the base `Facade` class and define a function called `getFacadeAccessor` which returns the name of the binding registered in the service container.

When a user references any static method on the `Cache` facade, Laravel resolves the `"cache"` binding from the service container and runs the requested method (in this case, get) against that object.

So basically, Facade is like Singleton - it's a static object that you can access from anywhere in your application.

When you call a method on a facade, **it passed the call to the underlying instance** of the class registered in the service container.
