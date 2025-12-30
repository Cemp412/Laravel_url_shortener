<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\API\CompanyController;
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

Route::get('/', function () {
    return view('welcome');
});

// Guest routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

Auth::routes(['register' => false]);
Route::get('/register', function (){
    return redirect('/');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth:sanctum', 'verified'])->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //SuperAdmin routes
    /* Route::middleware('role:superadmin')->group(function () {
    }); */

    //Superadmin and admin routes
    Route::middleware('role:superadmin|admin')->group(function() {
        Route::view('/clients/list', 'clients.view')->name('clients.list');
    });
});
