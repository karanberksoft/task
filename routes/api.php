<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DutyController;
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

Route::post('/register', [AuthController::class, 'Register']);
Route::post('/login', [AuthController::class, 'Login']);
Route::post('/userdetail', [AuthController::class, 'UserDetail'])->middleware('jwt');
Route::post('/refreshtoken', [AuthController::class, 'RefreshToken'])->middleware('jwt');
Route::post('/logout', [AuthController::class, 'Logout'])->middleware('jwt');

Route::post('/addduty', [DutyController::class, 'AddDuty'])->middleware('jwt');
Route::post('/updateduty/{id}', [DutyController::class, 'UpdateDuty'])->middleware('jwt');
Route::post('/deleteduty/{id}', [DutyController::class, 'DeleteDuty'])->middleware('jwt');
