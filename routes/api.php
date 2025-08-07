<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController; // Importa tu ProductController
use App\Http\Controllers\OrderController;   // Importa tu OrderController
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\RestauranteController;


// Ruta de usuario autenticado (si usas Sanctum para API tokens)
// Esta es la ruta por defecto que Laravel proporciona en api.php
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ----------------------------------------------------------------------
// Rutas para Productos (API RESTful)
// Utilizamos `Route::apiResource` para generar las 7 rutas RESTful estándar:
// GET    /api/products           -> ProductController@index
// POST   /api/products           -> ProductController@store
// GET    /api/products/{product} -> ProductController@show
// PUT    /api/products/{product} -> ProductController@update
// PATCH  /api/products/{product} -> ProductController@update
// DELETE /api/products/{product} -> ProductController@destroy
//
// NOTA: Si tu ProductController no tiene todos estos métodos, Laravel solo registrará los que existan.
// Esto es el equivalente a las rutas que tenías de apiResource en web.php.
Route::apiResource('products', ProductController::class);

// ----------------------------------------------------------------------
// Rutas para Pedidos (API RESTful)
// Es HIGHLY RECOMENDADO usar `Route::apiResource` aquí también.
// Esto generará:
// GET    /api/orders           -> OrderController@index
// POST   /api/orders           -> OrderController@store
// GET    /api/orders/{order}   -> OrderController@show
// PUT    /api/orders/{order}   -> OrderController@update
// PATCH  /api/orders/{order}   -> OrderController@update
// DELETE /api/orders/{order}   -> OrderController@destroy
//
// Esto reemplaza y simplifica las rutas individuales que tenías para orders en web.php
// (GET index, GET show, POST store, PUT updateStatus)

Route::apiResource('orders', OrderController::class);

// Si, por alguna razón, realmente necesitas la ruta específica `updateStatus`
// Y no quieres usar el método `update` en tu `OrderController` para el cambio de estado,
// podrías mantenerla así:
// Route::put('orders/{order}/status', [OrderController::class, 'updateStatus']);
//
// PERO: Te recomiendo encarecidamente usar el método `update` del `OrderController`
// (como lo he modificado en la respuesta anterior) y hacer la petición PATCH a /api/orders/{id}
// enviando solo el `status` en el cuerpo. Esto es más "RESTful" y más limpio.
// Si sigues mi recomendación, ¡esta línea de abajo NO LA NECESITARÁS!
// Route::put('orders/{order}/status', [OrderController::class, 'update']); // Usando el método 'update' si lo renombraste

Route::get('/analytics', [AnalyticsController::class, 'dashboard']);
Route::get('/analytics/monthly-summary', [AnalyticsController::class, 'monthlySummary']);
Route::post('/restaurantes', [RestauranteController::class, 'store']);