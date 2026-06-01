<?php

use Illuminate\Support\Facades\Route;

/*
|===========================================================================
| Semana 10 — Servicios de Transferencia de Archivos
| Curso: Evaluación y Control de Servicios Web
|===========================================================================
| Estas rutas sirven las vistas didácticas del tema.
| El API REST está definido en routes/api.php
|===========================================================================
*/

// Página principal: portada educativa con el flujo de transferencia
Route::get('/', function () {
    return view('welcome');
});

// Demo interactivo: upload con validación paso a paso
Route::get('/demo/upload', function () {
    return view('demo.upload');
});

// Demo auth: login/registro para obtener token de API
Route::get('/demo/auth', function () {
    return view('demo.auth');
});
