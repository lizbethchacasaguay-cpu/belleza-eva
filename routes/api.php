<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FavoriteController; 
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;


// Ruta de prueba
Route::get('/test', function() {
    return response()->json(['message' => 'API funcionando correctamente']);
});

// Ruta de prueba con autenticación
Route::middleware('auth:sanctum')->get('/test-auth', function(Request $request) {
    return response()->json([
        'message' => 'Autenticado correctamente',
        'user' => $request->user(),
        'isAdmin' => $request->user()->isAdmin()
    ]);
});

// Rutas públicas (sin token)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ✅ RUTA PÚBLICA PARA MOSTRAR PRODUCTOS AL FRONTEND
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']); // ✅ PÚBLICO: Detalles de producto
Route::get('/comments/product/{product_id}', [CommentController::class, 'showByProduct']); // ✅ PÚBLICO: Ver comentarios


// Rutas protegidas (requieren token)
Route::middleware('auth:sanctum')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // CRUD de productos (SOLO ADMIN)
    Route::middleware('admin')->group(function () {
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        // GESTIÓN DE USUARIOS (SOLO ADMIN)
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::put('/users/{id}/role', [UserController::class, 'changeRole']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
    });

    // Favoritos
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites', [FavoriteController::class, 'store']);
    Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy']);

    // Comentarios
    Route::post('/comments', [CommentController::class, 'store']);
    
});


