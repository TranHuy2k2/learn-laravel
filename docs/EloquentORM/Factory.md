---
weight: 3
---

When `testing` your application or `seeding` your database, you may need to insert a few records into your database. Instead of manually specifying the value of each column, Laravel allows you to **define a set of default attributes** for each of your Eloquent models using model factories.

```php
namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }
}
```

The definition method returns the default set of attribute values that should be applied when creating a model using the factory.

Via the `fake` helper, factories have access to the `Faker PHP` library, which allows you to conveniently generate various kinds of random data for testing and seeding.

## Define Model's Factory

```bash
php artisan make:factory PostFactory
```

## Creating the model from factory

```php
use App\Models\User;

$user = User::factory()->make();

// or

$users = User::factory()->count(3)->make(); // Returns a collection of 3 models.
```
