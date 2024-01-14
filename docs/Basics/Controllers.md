---
weight: 3
---

# Controllers

## Basic Controllers

To generate a new controller, use the command from Artisan `make:controller`:

```bash
php artisan make:controller UserController
```

After generating a controller, simply add methods to the class. Once you have written a controller class and method, you may define a route to the controller method like so:

```php
use App\Http\Controllers\UserController;

Route::get('/user/{id}', [UserController::class, 'show']);

```

## Controllers Middleware

Middleware may be assigned to the controller's routes in your route files:

```php
Route::get('profile', [UserController::class, 'show'])->middleware('auth');
```

Or you may make use of the `middleware` method to assign middleware to the controller's action:

```php
class UserController extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('log')->only('index');
        $this->middleware('subscribed')->except('store');
    }
}
```
