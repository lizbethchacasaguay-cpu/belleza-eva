<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FavoriteController; 
use App\Http\Controllers\CommentController;


// Ruta de prueba
Route::get('/test', function() {
    return response()->json(['message' => 'API funcionando correctamente']);
});

// Rutas públicas (sin token)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas (requieren token)
Route::middleware('auth:sanctum')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // CRUD de productos
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    // Rutas de favoritos

    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites', [FavoriteController::class, 'store']);
    Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy']);

    // COMENTARIOS
   Route::post('/comments', [CommentController::class, 'store']);
   Route::get('/comments/product/{product_id}', [CommentController::class, 'showByProduct']);
   Route::delete('/comments/{id}', [CommentController::class, 'destroy']);


});


