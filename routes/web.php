<?php

use App\Http\Controllers\ScraperController;
use Illuminate\Support\Facades\Route;
use App\Livewire\CalculadoraMaritima;
use App\Livewire\CalculadoraAerea;
use App\Livewire\CalculadoraTerrestre;
use App\Livewire\CalculadoraImpuestos;

/**
 * APIFY ROUTES
 */

Route::any('/scrape-product', [ScraperController::class, 'scrape'])->name('scrape_product');
Route::get('/scrape-status/{runId}', [ScraperController::class, 'status'])->name('scrape.status');
Route::get('/scrape-stream/{runId}', [ScraperController::class, 'stream'])->name('scrape.stream');

// Landing Page principal
Route::get('/', function () {
    return view('welcome-new');
})->name('home');

// Página Nosotros
Route::get('/nosotros', function () {
    return view('nosotros');
})->name('nosotros');

// Ruta de Login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Ruta de Registro
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Rutas de las calculadoras
Route::get('/maritimo', CalculadoraMaritima::class)->name('calculadora.maritima');
Route::get('/aereo', CalculadoraAerea::class)->name('calculadora.aerea');
Route::get('/terrestre', CalculadoraTerrestre::class)->name('calculadora.terrestre');
Route::get('/impuestos', CalculadoraImpuestos::class)->name('calculadora.impuestos');
// Rutas de servicios adicionales
Route::get('/importaciones-exportaciones', function () {
    return view('importaciones-exportaciones');
})->name('importaciones.exportaciones');

Route::get('/capacitaciones', function () {
    return view('capacitaciones');
})->name('capacitaciones');

Route::get('/logistica-transporte', function () {
    return view('logistica-transporte');
})->name('logistica.transporte');

Route::get('/criptomonedas', function () {
    return view('criptomonedas');
})->name('criptomonedas');

Route::get('/ecommerce', function () {
    return view('ecommerce');
})->name('ecommerce');

Route::get('/subastas', function () {
    return view('subastas');
})->name('subastas');
