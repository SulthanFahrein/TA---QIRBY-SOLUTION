<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login_Controller;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\Data_UserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;

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


Route::get('/master', function () {
    return view('components.template.master');
});

// Route::get('/home', function () {
//     return view('components.pages.home');
// });

Route::get('/property', function () {
    return view('components.pages.management');
});

Route::get('/data-user', [data_userController::class, 'index'])->name('users.index');

// Coba di save




// Route::get('/data_user/{data_user}/edit', [data_UserController::class, 'edit'])->name('users.edit');
// Route::put('/data_users/{data_user}', [data_UserController::class, 'update'])->name('users.update');
// Route::delete('/data_users/{data_user}', [data_UserController::class, 'destroy'])->name('users.destroy');
Route::resource('users', data_UserController::class);
Route::delete('/destroy/{id}', [Data_UserController::class, 'destroy'])->whereNumber('id')->name('data_user.destroy');

// Route::get('/', function () {
//     return view('components.pages.login');
// });


// === New ===
Route::get('/', [Login_Controller::class, 'index'])->name('admin.landing');
Route::get('/login', function () {
    return view('pages.login');
});
Route::post('/', [Login_Controller::class, 'authenticate'])->name('admin.login.auth');

Route::get('/admin', function () {
    $pengguna = Auth::user();

    return view('pages.admin', [
        'pengguna' => $pengguna
    ]);
})->name('admin');

Route::group(['middleware' => ['admin.auth']], function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/home', [DashboardController::class, 'schedule'])->name('admin.dashboard');
    // Route::get('data-user', [Data_UserController::class, 'index'])->name('property.data-user');

    Route::group(['prefix' => 'property'], function () {
        Route::get('/', [PropertyController::class, 'index'])->name('property.view');
        Route::post('/store', [PropertyController::class, 'store'])->name('property.store');
        Route::get('/edit/{id}', [PropertyController::class, 'edit'])->name('property.edit');
        Route::put('/update/{id}', [PropertyController::class, 'update'])->name('property.update');
        Route::delete('/destroy/{id}', [PropertyController::class, 'deleted'])->name('property.deleted');
        Route::get('/images/{imageId}', [PropertyController::class, 'deleteImage'])->name('property.deleteImage');

    });

    // Route::group(['prefix' => 'schedule'], function () {
    //     Route::get('/', [ScheduleController::class, 'index'])->name('schedule');

    // });

    Route::prefix('schedule')->group(function () {
        Route::get('/', [ScheduleController::class, 'indeks'])->name('schedule');
        Route::post('/store', [ScheduleController::class, 'tambah'])->name('schedule.store');
        Route::put('/update/{id}', [ScheduleController::class, 'update'])->name('schedule.update');
        Route::delete('/destroy/{id}', [ScheduleController::class, 'hapus'])->whereNumber('id')->name('schedule.destroy');
    });

    Route::prefix('admin')->group(function () {
        Route::name('admin.')->group(function () {
            Route::put('update', [AdminController::class, 'update'])->name('update');
        });
    });
});


//Route::post('/home, [BiodataController::class, 'home']);
