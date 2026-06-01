<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/*
|===========================================================================
| AvatarController
|===========================================================================
| Controlador para la subida, consulta y eliminación de avatares de usuario.
| Demuestra las validaciones del lado servidor para archivos:
|   - Tipo MIME real (por contenido, no por extensión)
|   - Tamaño máximo
|   - Dimensiones mínimas y máximas
|   - Almacenamiento con nombre UUID seguro
|   - Checksum SHA-256 para verificar integridad
|===========================================================================
*/

class AvatarController extends Controller
{
    /*
    | Configuración de validaciones
    |----------------------------------
    | Estos valores definen los límites que se aplican a todos los uploads.
    | Se pueden mover a config/avatars.php parafacilitar su mantenimiento.
    */
    private array $mimesPermitidos = ['image/jpeg', 'image/png', 'image/webp'];
    private int $tamanoMaximo = 2048;          // KB
    private int $dimensionMinima = 100;         // px
    private int $dimensionMaxima = 1024;        // px

    /*
    |--------------------------------------------------------------------------
    | POST /api/avatar
    |--------------------------------------------------------------------------
    | Sube un avatar para el usuario autenticado.
    | Requiere: auth:sanctum
    | Body: multipart/form-data con campo "avatar"
    |
    | Flujo de validación:
    | 1. Reglas de Laravel: required, file, mimes, max
    | 2. Validación personalizada de dimensiones con getimagesize()
    | 3. Eliminar avatar anterior si existe
    | 4. Generar nombre UUID seguro
    | 5. Almacenar en disco público
    | 6. Calcular SHA-256 para integridad
    | 7. Actualizar registro del usuario
    | 8. Responder con URL, tamaño, MIME y checksum
    |--------------------------------------------------------------------------
    */
    public function upload(Request $request)
    {
        /*
        | Validación con reglas de Laravel + closure personalizado
        | La regla 'mimes' verifica la extensión,
        | pero el closure usa getimagesize() para validar el contenido real.
        */
        $request->validate([
            'avatar' => [
                'required',                             // Archivo obligatorio
                'file',                                 // Debe ser un archivo
                'mimes:jpg,jpeg,png,webp',              // Extensiones permitidas
                'max:' . $this->tamanoMaximo,           // Tamaño máximo en KB
                function ($attribute, $value, $fail) {
                    /*
                    | Validación de dimensiones reales de la imagen
                    | getimagesize() lee los metadatos reales del archivo,
                    | no confía en la extensión ni en los headers HTTP.
                    */
                    $dimensions = @getimagesize($value->getPathname());
                    if (!$dimensions) {
                        $fail('El archivo no es una imagen válida.');
                        return;
                    }
                    [$w, $h] = $dimensions;
                    if ($w < $this->dimensionMinima || $h < $this->dimensionMinima) {
                        $fail("La imagen debe medir al menos {$this->dimensionMinima}×{$this->dimensionMinima}px.");
                    }
                    if ($w > $this->dimensionMaxima || $h > $this->dimensionMaxima) {
                        $fail("La imagen no debe exceder {$this->dimensionMaxima}×{$this->dimensionMaxima}px.");
                    }
                },
            ],
        ]);

        // Obtener usuario autenticado mediante Sanctum
        $user = $request->user();
        $archivo = $request->file('avatar');

        /*
        | Si el usuario ya tenía avatar, eliminar el archivo anterior
        | para no acumular archivos huérfanos en el disco.
        */
        if ($user->avatar_url) {
            Storage::disk('public')->delete($user->avatar_url);
        }

        /*
        | Generar nombre único con UUID
        | Esto evita colisiones de nombres y ataques de path traversal.
        | Ejemplo: avatars/550e8400-e29b-41d4-a716-446655440000.jpg
        */
        $nombre = 'avatars/' . Str::uuid() . '.' . $archivo->getClientOriginalExtension();

        /*
        | Almacenar el archivo en el disco público
        | storage/app/public/avatars/ se enlaza simbólicamente a public/storage/
        */
        $ruta = Storage::disk('public')->putFileAs('avatars', $archivo, basename($nombre));

        /*
        | Actualizar el campo avatar_url del usuario en la base de datos
        | El modelo User tiene el método updateAvatar() para esto
        */
        $user->updateAvatar($ruta);

        /*
        | Calcular el hash SHA-256 del archivo almacenado
        | El cliente puede comparar este hash con el suyo para verificar
        | que el archivo se transfirió sin corrupción.
        */
        $hashReal = hash_file('sha256', storage_path("app/public/$ruta"));

        /*
        | Responder con los datos del archivo subido
        | Código 201: Created (recurso creado exitosamente)
        */
        return response()->json([
            'message' => 'Avatar actualizado correctamente',
            'data' => [
                'url'      => Storage::url($ruta),      // URL pública del avatar
                'tamano'   => $archivo->getSize(),       // Tamaño en bytes
                'mime'     => $archivo->getMimeType(),   // Tipo MIME real
                'checksum' => $hashReal,                  // SHA-256 para integridad
            ],
        ], 201);
    }

    /*
    |--------------------------------------------------------------------------
    | GET /api/avatar/{user}
    |--------------------------------------------------------------------------
    | Obtiene la URL del avatar de un usuario por su ID.
    | Es público (no requiere autenticación).
    |
    | Si el usuario no tiene avatar, devuelve una URL por defecto.
    |--------------------------------------------------------------------------
    */
    public function show(User $user)
    {
        /*
        | Verificar si el usuario tiene avatar y si el archivo existe en disco
        */
        if (!$user->avatar_url || !Storage::disk('public')->exists($user->avatar_url)) {
            return response()->json([
                'message' => 'El usuario no tiene avatar',
                'data' => [
                    'url' => '/img/avatar-default.png',  // Imagen por defecto
                ],
            ]);
        }

        return response()->json([
            'data' => [
                'url' => Storage::url($user->avatar_url),
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE /api/avatar
    |--------------------------------------------------------------------------
    | Elimina el avatar del usuario autenticado.
    | Requiere: auth:sanctum
    |
    | Elimina el archivo del disco y pone avatar_url=null en la BD.
    |--------------------------------------------------------------------------
    */
    public function destroy(Request $request)
    {
        $user = $request->user();

        if (!$user->avatar_url) {
            return response()->json([
                'message' => 'No hay avatar para eliminar',
            ], 404);
        }

        // Eliminar archivo físico del disco
        Storage::disk('public')->delete($user->avatar_url);

        // Actualizar base de datos
        $user->update(['avatar_url' => null]);

        return response()->json([
            'message' => 'Avatar eliminado correctamente',
        ]);
    }
}
