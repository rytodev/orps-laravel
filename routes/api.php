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

Route::get('/get-notes', [Controllers\NoteApiController::class, 'index']);
Route::get('/show-note/{id}', [Controllers\NoteApiController::class, 'show']);
Route::post('/add-note', [Controllers\NoteApiController::class, 'store']);
Route::put('/update-note/{id}', [Controllers\NoteApiController::class, 'update']);
Route::delete('/delete-note/{id}', [Controllers\NoteApiController::class, 'delete']);

//Auth
Route::post('/login', [Controllers\AuthApiController::class, 'login']);
