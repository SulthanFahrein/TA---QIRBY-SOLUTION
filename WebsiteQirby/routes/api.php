<?php

use App\Http\Controllers\Api\PropertyAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\AHPController;
use App\Http\Controllers\Api\ScheduleAPI;
use App\Http\Controllers\Api\DataUserAPI;
use App\Http\Controllers\Api\FavoriteAPI;


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

Route::group(['prefix' => 'property'], function () {
    Route::get('/', [PropertyController::class, 'index'])->name('property.view');
});


Route::get('calculate-ahp', [AHPController::class, 'calculateAHP']);

Route::prefix('property')->group(function () {
    Route::get('/', [PropertyAPI::class, 'index']);
    Route::get('/{id}', [PropertyAPI::class, 'show']);
    Route::post('/', [PropertyAPI::class, 'store']);
});
Route::prefix('schedule')->group(function () {
    Route::get('/', [ScheduleAPI::class, 'index']);
    Route::post('/', [ScheduleAPI::class, 'store']);
    Route::put('/{id}', [ScheduleAPI::class, 'update']);
    Route::delete('/{id_jadwal}', [ScheduleAPI::class, 'destroy']);
});

Route::prefix('data_user')->group(function () {
    Route::post('/register', [DataUserAPI::class, 'register']);
    Route::post('/login', [DataUserAPI::class, 'login']);
    Route::get('/users', [DataUserAPI::class, 'getAllUsers']);
    Route::get('/userlogin', [DataUserAPI::class, 'getDataLogin']);
    Route::get('/datauser', [DataUserAPI::class, 'getAllUsersNoToken']);
    Route::put('/edit/{id}', [DataUserAPI::class, 'editProfile']);
    Route::post('/upload-profile-image', [DataUserAPI::class, 'uploadProfileImage']);

});
Route::prefix('data_user')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/getuser', [DataUserAPI::class, 'get_my_profile']);
    Route::post('/password', [DataUserAPI::class, 'forgotPassword']);
    Route::put('/edit', [DataUserAPI::class, 'editProfile']);

});

Route::prefix('favorite')->group(function () {
    Route::get('/', [FavoriteAPI::class, 'index']);
    Route::post('/', [FavoriteAPI::class, 'store']);
    Route::put('/{id}', [FavoriteAPI::class, 'update']);
    Route::delete('/{id_favorite}', [FavoriteAPI::class, 'destroy']);
});

