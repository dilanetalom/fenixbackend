<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\NewsController;

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




Route::post('login', [AuthController::class, 'login'])->name('login');
Route::get('getbook', [BookController::class, 'index']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('allusers', [AuthController::class, 'allusers']);

    // Routes pour la gestion des livres
    
    Route::post('savebook', [BookController::class, 'store']);
    Route::post('saveauthor', [BookController::class, 'saveauthor']);
    Route::get('allauthor', [BookController::class, 'allauthor']);
    Route::get('getbyauthor/{id}', [BookController::class, 'getbyauthor']);
    Route::get('getbybook/{id}', [BookController::class, 'show']);
    Route::put('updatebook/{id}', [BookController::class, 'update']);
    Route::delete('deletebook/{id}', [BookController::class, 'destroy']);
    Route::delete('deleteauthor/{id}', [BookController::class, 'deleteauthor']);
    Route::post('filterBooks', [BookController::class, 'filterBooks']);
    Route::get('authorBooks/{id}', [BookController::class, 'edit']);
    Route::get('allbookbyuser/{id}', [BookController::class, 'allbookbyuser']);

    // Routes pour la gestion des Evennements
    Route::get('getevent', [EventController::class, 'index']);
    Route::post('saveevent', [EventController::class, 'store']);
    Route::get('getbyevent/{id}', [EventController::class, 'show']);
    Route::put('updateevent/{id}', [EventController::class, 'update']);
    Route::delete('deleteevent/{id}', [EventController::class, 'destroy']);

    // Routes pour la gestion des actualites
    Route::get('getnews', [NewsController::class, 'index']);
    Route::post('savenews', [NewsController::class, 'store']);
    Route::get('getbynews/{id}', [NewsController::class, 'show']);
    Route::put('updatenews/{id}', [NewsController::class, 'update']);
    Route::delete('deletenews/{id}', [NewsController::class, 'destroy']);
   
});
