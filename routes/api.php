<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ReaderController;
use App\Http\Controllers\SaleController;
use Darkaonline\L5Swagger\Swagger;

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
Route::post('register', [AuthController::class, 'register']);
Route::get('getbook', [BookController::class, 'index']);
Route::get('getbybook/{id}', [BookController::class, 'show']);
Route::get('allauthor', [BookController::class, 'allauthor']);
Route::get('getbyauthor/{id}', [BookController::class, 'getbyauthor']);
Route::get('getnews', [NewsController::class, 'index']);
Route::post('/readers', [ReaderController::class, 'store']);

Route::middleware('auth:api')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('authUser', [AuthController::class, 'authUser']);
    Route::get('allusers', [AuthController::class, 'allusers']);
    Route::delete('deleteuser/{id}', [AuthController::class, 'deleteuser']);
    Route::put('updateuser/{id}', [AuthController::class, 'update']);
    
    // Routes pour la gestion des livres
    
    Route::post('savebook', [BookController::class, 'store']);
    Route::post('saveauthor', [BookController::class, 'saveauthor']);
    Route::post('updatebook/{id}', [BookController::class, 'update']);
    Route::put('updateauthors/{id}', [BookController::class, 'updateAuthor']);
    Route::delete('deletebook/{id}', [BookController::class, 'destroy']);
    Route::delete('deleteauthor/{id}', [BookController::class, 'deleteauthor']);
    Route::post('filterBooks', [BookController::class, 'filterBooks']);
    Route::get('authorBooks/{id}', [BookController::class, 'edit']);
    Route::get('allbookbyuser/{id}', [BookController::class, 'allbookbyuser']);
    Route::get('count', [BookController::class, 'count']);
    
    // Routes pour la gestion des Evennements
    Route::get('getevent', [EventController::class, 'index']);
    Route::post('saveevent', [EventController::class, 'store']);
    Route::get('getbyevent/{id}', [EventController::class, 'show']);
    Route::post('updateevent/{id}', [EventController::class, 'update']);
    Route::delete('deleteevent/{id}', [EventController::class, 'destroy']);
    
    // Routes pour la gestion des actualites

    Route::post('savenews', [NewsController::class, 'store']);
    Route::get('getbynews/{id}', [NewsController::class, 'show']);
    Route::post('updatenews/{id}', [NewsController::class, 'update']);
    Route::delete('deletenews/{id}', [NewsController::class, 'destroy']);

    // gestion des lecteurs


    Route::get('/readers', [ReaderController::class, 'index']); // Récupérer tous les lecteurs
    Route::get('/readers/{id}', [ReaderController::class, 'show']); // Récupérer un lecteur
    // Route::post('/readers', [ReaderController::class, 'store']); // Créer un nouveau lecteur
    Route::put('/readers/{id}', [ReaderController::class, 'update']); // Mettre à jour un lecteur
    Route::delete('/readers/{id}', [ReaderController::class, 'destroy']);


    // gestion des commandes
    Route::get('/sale', [SaleController::class, 'index']); // Récupérer tous les lecteurs
    Route::get('/sale/{id}', [SaleController::class, 'show']); // Récupérer un lecteur
    Route::post('/sales', [SaleController::class, 'store']); // Créer un nouveau lecteur
    Route::put('/sale/{id}', [SaleController::class, 'update']); // Mettre à jour un lecteur
    Route::delete('/sale/{id}', [SaleController::class, 'destroy']);

   
});
