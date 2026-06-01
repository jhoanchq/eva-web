<?php

/*
|===========================================================================
| API Routes — Semana 10: Servicios de Transferencia de Archivos
|===========================================================================
|
| == AUTENTICACIÓN (pública) ==
| POST /api/register    → Crear cuenta + token
| POST /api/login       → Iniciar sesión + token
|
| == AVATAR (pública) ==
| GET  /api/avatar/{id} → Consultar avatar público
|
| == AVATAR (protegida con Sanctum) ==
| POST   /api/avatar    → Subir avatar
| DELETE /api/avatar    → Eliminar avatar
| GET    /api/user      → Datos del usuario autenticado
| POST   /api/logout    → Cerrar sesión
|
| Header requerido para rutas protegidas:
|   Authorization: Bearer {token}
|===========================================================================
*/

use App\Http\Controllers\Api\AvatarController;
use App\Http\Controllers\Auth\ApiAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// === RUTAS PÚBLICAS ===

// Autenticación
Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/login',    [ApiAuthController::class, 'login']);

// Avatar público
Route::get('/avatar/{user}', [AvatarController::class, 'show'])->whereNumber('user');

// === RUTAS PROTEGIDAS (requieren token Sanctum) ===
Route::middleware('auth:sanctum')->group(function () {

    // Usuario autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Cerrar sesión
    Route::post('/logout', [ApiAuthController::class, 'logout']);

    // Avatar
    Route::post('/avatar',   [AvatarController::class, 'upload']);
    Route::delete('/avatar', [AvatarController::class, 'destroy']);
});
