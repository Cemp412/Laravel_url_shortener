<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\InvitationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Guest routes 
Route::post('/invitations/accept/{token}', [InvitationController::class, 'acceptInvite'])->name('invitations.accept');

//Public routes
Route::controller(AuthController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login')->name('api.login');
});


// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // ... other protected API routes
    Route::middleware('role:superadmin')->group(function () {
        Route::apiResource('/companies', CompanyController::class)->only(['store']); //->name('store', 'companies.store');

    });

    Route::middleware('role:superadmin|admin')->group(function() {
        Route::post('/invitations', [InvitationController::class, 'store'])->name('api.invitations.store');
    });
    

    
});
