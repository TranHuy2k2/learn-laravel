---
weight: 2
---

# Relationships

## Defining relationship

Eloquent relationships are `defined as methods` on your Eloquent model classes. Since relationships also serve as powerful query builders, defining relationships as methods provides powerful method chaining and querying capabilities.

For example, we may chain additional query constraints on this posts relationship:

```php
$user->posts()->where('active', 1)->get();
```

### One To One

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Model
{
    /**
     * Get the phone associated with the user.
     */
    public function phone(): HasOne
    {
        return $this->hasOne(Phone::class);
    }
}
```

Dynamic properties allow you to access relationship methods as if they were properties defined on the model:

```php
$phone = User::find(1)->phone;
```

If you would like the relationship to use a primary key value `other than id or your model's $primaryKey` property, you may pass a third argument to the hasOne method:

```php
return $this->hasOne(Phone::class, 'foreign_key', 'local_key');
```

#### Inverse Of The Relationship

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Phone extends Model
{
    /**
     * Get the user that owns the phone.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
```
