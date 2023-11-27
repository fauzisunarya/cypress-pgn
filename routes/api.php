<?php
use App\Helper\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\BeforeRequest;
use App\Http\Controllers\ContentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'contents'], function() {
    Route::post('/list', [ContentController::class, 'index'])->middleware('auth');
    Route::post('/create', [ContentController::class, 'create'])->middleware('auth');
    Route::post('/update', [ContentController::class, 'update'])->middleware('auth');
    Route::post('/delete-content', [ContentController::class, 'delete'])->middleware('auth');
});
