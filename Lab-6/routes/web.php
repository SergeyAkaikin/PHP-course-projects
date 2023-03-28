<?php

use App\Http\Controllers\Web\HomeController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\Have;
use App\Models\Permissions\PermissionCode;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->middleware([Have::permissions(PermissionCode::AccessMusicCollection)]);
Route::get('login', [HomeController::class, 'login'])->withoutMiddleware(Authenticate::class);
Route::get('album/{album_id}', [HomeController::class, 'show'])
    ->whereNumber('album_id')
    ->middleware([Have::permissions(PermissionCode::AccessMusicCollection)]);
