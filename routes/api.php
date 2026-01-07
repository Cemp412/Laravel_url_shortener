<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\InvitationController;
use App\Http\Controllers\API\RolesAndPermController;
use App\Http\Controllers\API\ClientResourceController;
use App\Http\Controllers\API\TeamResourceController;
use App\Http\Controllers\API\MemberResourceController;

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
//Public routes
Route::controller(AuthController::class)->group(function(){
    // Route::post('register', 'register');
    Route::post('login', 'login')->name('api.login');
});


// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    /* Route::get('/user', function (Request $request) {
        return $request->user();
    }); */
    Route::post('/logout', [AuthController::class, 'logout']);

    // ... other protected API routes
    Route::middleware('role:superadmin')->group(function () {
        Route::apiResource('/companies', CompanyController::class)->only(['store']); //->name('store', 'companies.store');
        Route::apiResource('/clients', ClientResourceController::class)->only(['index']);
        Route::get('/generated-urls', [ClientResourceController::class, 'urls'])->name('clients.urls');

    });

    Route::middleware('role:superadmin|admin')->group(function() {
        Route::post('/invitations', [InvitationController::class, 'store'])->name('api.invitations.store');
        Route::get('/roles-list', [RolesAndPermController::class, 'getRoles'])->name('api.roles-list');
        
    });
    
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('/team', TeamResourceController::class)->only(['index']);
        Route::get('/team-generated-urls', [TeamResourceController::class, 'teamGeneratedUrls'])->name('api.teamGeneratedUrls');

    });

    Route::middleware('role:member')->group(function() {
        Route::get('/short-urls-list', [MemberResourceController::class, 'shortUrls'])->name('api.ShortUrls-list');
    });
    
});
