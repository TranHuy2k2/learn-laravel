<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

class Service
{
    public function __construct()
    {
        echo "Test dependency injection";
    }
}

Route::get('/', function (Service $service) {
    return view('welcome');
});

Route::get("/test", function () {
    $user = User::factory()->make();
    $result = ["test" => "test", "result" => [1, 2, 3, 4, 5], "user" => $user];
    return response()->json($result);
});

Route::get("/login", function () {
    return view("login");
});
