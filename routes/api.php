<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HouseController;
use App\Http\Controllers\DamageImageController;



use App\Http\Resources\UserResource;
use App\Models\User;

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

Route::post('auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->post('/house/add', [HouseController::class, 'store']);
Route::middleware('auth:sanctum')->post('/houses', [HouseController::class, 'index']);
Route::middleware('auth:sanctum')->delete('/house/delete', [HouseController::class, 'destroy']);
Route::middleware('auth:sanctum')->put('/house/{id}/update', [HouseController::class, 'updateHouse']);

Route::middleware('auth:sanctum')->post('/house/damage-images', [DamageImageController::class, 'store']);



// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::get('/user/{id}', function (string $id) {
    return new UserResource(User::findOrFail($id));
});

// Route::get('/users', function () {
//     return UserResource::collection(User::all());
// });




