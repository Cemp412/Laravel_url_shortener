<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\Web\RegisterInviteController;
// use App\Mail\TestMail;
// use Illuminate\Support\Facades\Mail;

/* Route::get('/send-test', function () {
    Mail::to('recipient@example.com')->send(new TestMail());
    return 'Test email sent!';
}); */
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
Route::middleware('guest')->group(function() {
    Route::get('/register', function (){
        return redirect('/');
    });
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    // Route::view('/login-new', 'auth.login-new')->name('login.new');
    Route::get('/register-via-invite/{token}', [RegisterInviteController::class, 'show'])->name('register-invite');
    Route::post('/register-via-invite/{token}', [RegisterInviteController::class, 'store'])->name('accept-invite');
    

});


Auth::routes(['register' => false]);

// Auth routes
Route::middleware(['auth:sanctum', 'verified'])->group(function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //SuperAdmin routes
    /* Route::middleware('role:superadmin')->group(function () {
    }); */

    //Superadmin and admin routes
    Route::middleware('role:superadmin|admin')->group(function() {
        Route::view('/clients/list', 'clients.view')->name('clients.list');
    });
});

//404 route
Route::fallback(function () {
    return response()->view('404', [], 404);
});
