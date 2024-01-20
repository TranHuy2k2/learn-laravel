---
weight: 4
---

# Serializing Models and Collections

## Serializing to arrays and JSON

To convert a model and its loaded relationships `to an array`, you should use the `toArray` method. This method is recursive, so all attributes and all relations (including the relations of relations) will be converted to arrays:

```php
use App\Models\User;

$user = User::with('roles')->first();

return $user->toArray();
```

The same for JSON with the method `toJson()`

## Manipulating the JSON

### Hiding attributes from JSON

You may use the `visible` property to define an "allow list" of attributes that should be included in your model's array and JSON representation.

All attributes that are not present in the `$visible` array will be hidden when the model is converted to an array or JSON:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The attributes that should be visible in arrays.
     *
     * @var array
     */
    protected $visible = ['first_name', 'last_name'];
}
```

You can also temporarily add the hidden attributes to the model's visible attributes using the `makeVisible` method:

```php

return $user->makeVisible('attribute')->toArray();
```

Likewise for `makeHidden` method, it used to hide an attribute:

```php
return $user->makeHidden('attribute')->toArray();
```

### Appending values to JSON

Occasionally, when converting models to arrays or JSON, you may wish to `add attributes` that do not have a corresponding column in your `database`. You need to define an accessor for the value;

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * Determine if the user is an administrator.
     */
    protected function isAdmin(): Attribute
    {
        return new Attribute(
            get: fn () => 'yes',
        );
    }
}
```

Then, you may use the `append` property to add the value to the model's array or JSON representation:

```php
return $user->append('is_admin')->toArray();

return $user->setAppends(['is_admin'])->toArray();

```
