<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BlogController;

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
Route::post('/register', [AuthController::class, 'createUser']);
Route::post('/login', [AuthController::class, 'loginUser']);
Route::get('/user', [UserController::class, 'getUser'])->middleware('auth:sanctum');
Route::post('/blog/store', [BlogController::class, 'store'])->middleware('auth:sanctum');
Route::get('/blog/edit/{id}', [BlogController::class, 'edit'])->middleware('auth:sanctum');
Route::get('/blog/allblog', [BlogController::class, 'allblog']);
Route::get('/blog/myblog', [BlogController::class, 'myblog'])->middleware('auth:sanctum');
Route::get('/blog/show/{id}', [BlogController::class, 'show']);
Route::get('/blog/search/{keywords}', [BlogController::class, 'search']);