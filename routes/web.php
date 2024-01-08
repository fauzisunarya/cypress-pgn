<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Foundation\Application;
use App\Http\Middleware\BeforeRequest;

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

Route::get('/', function () {
    return redirect('contents/list');
});

Route::group(['middleware' => [BeforeRequest::class], 'prefix' => 'contents'], function()
    {
        Route::get('/list', function() {
            return Inertia::render('Content/List');
        });
    }
);

require __DIR__.'/auth.php';
