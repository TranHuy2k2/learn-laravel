---
weight: 5
---

# Mutators & Casting

## Introduction

`Accessors`, `mutators`, and `attribute casting` allow you to transform Eloquent attribute values when you retrieve or set them on model instances

## Accessors

### Defining the Accessor

To define an accessor, create a **protected method** on your model to represent the accessible attribute. This method name should correspond to the _"camel case"_ representation of the true underlying `model attribute / database column` when applicable.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * Get the user's first name.
     */
    protected function firstName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
        );
    }
}
```
