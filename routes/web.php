<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome'); // O la vista principal de tu aplicación si Laravel la sirve
});

// Remueve cualquier otra ruta de API (products, orders) que haya estado aquí
// Esas rutas ahora pertenecen a api.php