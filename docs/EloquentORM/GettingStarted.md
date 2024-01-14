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
