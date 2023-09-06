<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\DepartController;
use App\Http\Controllers\API\DepartementController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\TypeServiceController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LocationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
});



Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('/users', UserController::class);
Route::apiResource('/categorie', CategoryController::class);
Route::apiResource('/typeService', TypeServiceController::class);
Route::apiResource('/departement', DepartementController::class);
Route::apiResource('/service',ServiceController::class);
Route::apiResource('/client',ClientController::class);

Route::post(
    '/departement/{departementId}/add-user/{userId}',
    [DepartementController::class, 'addColabToDepart']
);
Route::delete(
    '/departement/{departementId}/remove-colab/{userId}',
    [DepartementController::class, 'removeColabFromDepart']
);

/**
 * Service API
 */
Route::get('/services/{service}/type-services',[ServiceController::class,'getTypeServices']);

/**
 * Type Service API
 */
Route::get('/type-services/{typeService}/durations', [TypeServiceController::class, 'getDurationsByTypeService']);
Route::delete('/type-services/{typeService}/durations/{duration}', [TypeServiceController::class, 'deleteDuration']);

/**
 * Location API
 */

Route::get('/get-location', [LocationController::class, 'getLocationUser']);

Route::get('client-users', [UserController::class,'getClientUsers']);


