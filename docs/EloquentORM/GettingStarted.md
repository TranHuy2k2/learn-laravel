---
weight: 1
---

# Getting Started

## Introduction

Eloquent is Laravel's default ORM (Object Relational Mapper). It supports multiple database systems and is built on top of the PDO (PHP Data Objects) database abstraction layer.

Currently, Eloquent ORM supports four relational database systems:

-   MySQL
-   Postgres
-   SQLite
-   SQL Server

Each table of a database will me mapped to one Model class. Models allow you to query for data in your tables, as well as update of the table.

## Generating Model

To generate a new model, use the command from Artisan `make:model`:

```bash
php artisan make:model Flight
```

This command will create a new model class in the `app/Models` directory.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;
}
```

## Defining Model

### Table's name

By convention, the "snake case", plural name of the class will be used as the table name unless another name is explicitly specified.

So the model named Flight will become the table `flights`.
Or you can change the table name by:

```php
class Flight extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'my_flights';
}
```

### Primary Keys

By default, Eloquent expects the primary key of the table to be an auto-incrementing integer named `id`.

Instead of using the default primary key, you can define a protected `$primaryKey` property on your model:

You can also use UUID as primary key:

```php
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasUuids;

    // ...
}

$article = Article::create(['title' => 'Traveling to Europe']);

$article->id; // "8f8e8478-9035-4d23-b9a7-62f4d2612ce5"
```

For more about primary keys, see [Eloquent: Getting Started](https://laravel.com/docs/10.x/eloquent#primary-keys).

### Timestamps

By default, Eloquent expects created_at and updated_at columns to exist on your model's corresponding database table. Eloquent will automatically set the values of these columns when models are created or updated.

You can set the `$timestamps` property on your model to `false` to disable this behavior.

### Retrieving Models

You can think of each Eloquent model as a powerful query builder allowing you to fluently query the database table associated with the model.

The model's all method will retrieve all of the records from the model's associated database table:

```php
use App\Models\Flight;

foreach (Flight::all() as $flight) {
    echo $flight->name;
}
```

Since each Eloquent model serves as a query builder, you may add additional constraints to queries and then invoke the get method to retrieve the results:

```php
$flights = Flight::where('active', 1)
               ->orderBy('name')
               ->take(10)
               ->get();
```

#### Refreshing models

If you already have an instance of an Eloquent model that was retrieved from the database, you can "`refresh`" the model using the fresh and refresh methods. The fresh method will `re-retrieve` the model from the database. The existing model instance will not be affected:

```php
$flight = Flight::where('number', 'FR 900')->first();

$freshFlight = $flight->fresh();
```

### Collections

As we have seen, Eloquent methods like `all` and `get` retrieve multiple records from the database. However, these methods don't return a plain PHP array. Instead, an instance of `Illuminate\Database\Eloquent\Collection` is returned.

This Class provides a variety of helpful methods for working with your Eloquent results:

For example, `reject` method may be used to remove items from the collection based on the results of a truth test:

```php
$flights = Flight::where('destination', 'Paris')->get();

$flights = $flights->reject(function (Flight $flight) {
    return $flight->cancelled;
});
```

### Chunking results

Your application may run out of memory if you attempt to load tens of thousands of Eloquent records via the `all` or `get` methods. Instead of using these methods, the `chunk` method may be used to process large numbers of models more efficiently.
