<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskResourceController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware(['logExecutionTime'])->group(function () {

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware("auth:sanctum")->group( function () {
    Route::get('/tasks', [TaskController::class, 'list']);  
    Route::post('/tasks', [TaskController::class, 'create']); 
    Route::put('/tasks/{id}/assign', [TaskController::class, 'assign']); 
    Route::put('/tasks/{id}/complete', [TaskController::class, 'complete']);

    Route::get('/tasks-list', [TaskResourceController::class, 'getTransformedTasks']); 
    Route::get('/tasks/{id}', [TaskResourceController::class, 'show']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

});

});