<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
|===========================================================================
| Migración: Agregar avatar_url a la tabla users
|===========================================================================
| Esta migración añade el campo avatar_url a la tabla users existente
| para almacenar la ruta del avatar de perfil de cada usuario.
|===========================================================================
*/

return new class extends Migration
{
    /*
    | Ejecutar la migración: agrega la columna avatar_url
    | después del campo password, permitiendo valores nulos
    | (el usuario puede no tener avatar)
    */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Campo string, nullable (puede ser null si no hay avatar)
            // after('password') lo coloca después de la columna password en la BD
            $table->string('avatar_url')->nullable()->after('password');
        });
    }

    /*
    | Revertir la migración: elimina la columna avatar_url
    */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar_url');
        });
    }
};
