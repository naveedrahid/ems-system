<?php

use App\Http\Controllers\JobPortalControllers\JobController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::prefix('api')->middleware(['auth:sanctum', 'role:1,2', 'check.user.status'])->group(function () {
// });

Route::middleware('auth:sanctum')->post('v1/jobs', [JobController::class, 'store']);




// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();    
// });
