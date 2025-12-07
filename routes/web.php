<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// RUTA DE PRUEBA TEMPORAL
Route::get('/test-web', function() {
    return response()->json(['message' => 'Ruta WEB funcionando correctamente']);
});