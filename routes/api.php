<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//note
Route::get('/get-notes', [Controllers\NoteApiController::class, 'index'])->middleware('auth:sanctum');
Route::get('/show-note', [Controllers\NoteApiController::class, 'show'])->middleware('auth:sanctum');;
Route::post('/add-note', [Controllers\NoteApiController::class, 'store'])->middleware('auth:sanctum');
Route::put('/update-note', [Controllers\NoteApiController::class, 'update'])->middleware('auth:sanctum');;
Route::delete('/delete-note', [Controllers\NoteApiController::class, 'destroy'])->middleware('auth:sanctum');;

//Auth
Route::post('/login', [Controllers\AuthApiController::class, 'login']);
Route::post('/logout', [Controllers\AuthApiController::class, 'logout'])->middleware('auth:sanctum');
