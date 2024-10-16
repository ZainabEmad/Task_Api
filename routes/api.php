<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserProfileController;


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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout'); 
    Route::post('/refresh', 'refresh');
});

    Route::middleware(['jwt.verify'])->group(function() {
        Route::get('/user_profile', [UserProfileController::class, 'user_profile']);
        Route::post('/update_user_profile/{id}', [UserProfileController::class, 'update_user_profile']);
        Route::get('/tasks', [TaskController::class, 'index']);
        Route::get('task/{id}', [TaskController::class, 'show']);
        Route::post('/store_task', [TaskController::class, 'store']);
        Route::post('/task/{id}', [TaskController::class, 'update']);
        Route::post('/tasks/{id}', [TaskController::class, 'destroy']);
        Route::post('/completed_tasks/{id}', [TaskController::class, 'markAsCompleted']);
        Route::post('/pending_tasks/{id}', [TaskController::class, 'markAsPending']);
        Route::get('/tasks/filter', [TaskController::class, 'filterTasks']);

    });