---
weight: 2
---

# Service Container

The Laravel service container is a powerful tool for managing class dependencies and performing dependency injection.

Injecting User Repository into Controller:

```php
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected UserRepository $users,
    ) {}

    /**
     * Show the profile for the given user.
     */
    public function show(string $id): View
    {
        $user = $this->users->find($id);

        return view('user.profile', ['user' => $user]);
    }
}
```

## Zero Configuration Resolution

If a class has no dependencies or only depends on other concrete classes (not interfaces), the container does not need to be instructed on how to resolve that class.

For example, you may place the following code in your `routes/web.php` file:

```php
<?php

class Service
{
    public function __construct()
    {
        echo "Test dependency injection";
    }
}

Route::get('/', function (Service $service) {
    die($service::class);
});
```

This will output `Test dependency injection` even though we did not bind the `Service` class into the container. The container was able to resolve the class and inject it into the route's closure.
