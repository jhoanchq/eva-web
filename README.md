# 🚀 Evaluación y Control de Servicios Web — Semana 10

> **Tema:** Servicios de Transferencia de Archivos  
> **Framework:** Laravel 11 + Sanctum (API REST)  
> **Docente:** Jhoan Benito Chite Quispe  
> **Período:** 2026-I

---

## 📋 Requisitos

| Herramienta | Versión | Ruta en Laragon |
|-------------|---------|-----------------|
| PHP | ≥ 8.2 | `C:\laragon\bin\php\php-8.2.30-Win32-vs16-x64` |
| Composer | ≥ 2.5 | `C:\laragon\bin\composer\composer.phar` |
| MySQL | ≥ 8.0 | `C:\laragon\bin\mysql\mysql-8.0.30-winx64` |
| Node.js | ≥ 18 | (opcional, para Vite) |

---

## 🛠️ Instalación paso a paso

### 1. Clonar el repositorio

```bash
cd C:\laragon\www
git clone https://github.com/jhoanchq/eva-web.git
cd eva-web
```

### 2. Instalar dependencias PHP

```bash
composer install
```

### 3. Configurar entorno

```bash
cp .env.example .env
```

Editar `.env` con tus datos:

```env
APP_NAME="Evaluación Web"
APP_ENV=local
APP_URL=http://eva-web.test
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eva_web
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generar key de la aplicación

```bash
php artisan key:generate
```

### 5. Crear la base de datos

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS eva_web CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 6. Ejecutar migraciones

```bash
php artisan migrate
```

### 7. Instalar Sanctum (API tokens)

```bash
php artisan install:api
```

### 8. Crear enlace simbólico para uploads

```bash
php artisan storage:link
```

### 9. (Opcional) Crear usuario de prueba

```bash
php artisan tinker
```

```php
\App\Models\User::factory()->create([
    'name' => 'Juan Perez',
    'email' => 'juan@test.com',
    'password' => bcrypt('password'),
]);
```

### 10. Configurar envío de correo (Mailtrap)

1. Crea una cuenta gratis en https://mailtrap.io
2. Ve a **Email Testing** → **My Inbox** → copia las credenciales SMTP
3. Edita tu `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_username
MAIL_PASSWORD=tu_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@eva-web.test"
MAIL_FROM_NAME="EVA-WEB"
```

4. Al registrarse un usuario (`POST /api/register`), se envía automáticamente un correo de bienvenida al nuevo usuario.

---

## 🚀 Cómo usar

### Iniciar servidor de desarrollo

```bash
php artisan serve
# Abrir: http://127.0.0.1:8000
```

O desde **Laragon**: inicia Laragon y abre `http://eva-web.test`

### Flujo del estudiante

```
Portada educativa  →  Registro/Login  →  Obtener Token  →  Demo Upload
   ┌──────────┐       ┌──────────┐       ┌──────────┐       ┌──────────┐
   │  GET /   │   →   │ /demo/auth│   →   │ Obtener  │   →   │/demo/    │
   │          │       │           │       │ Token    │       │ upload   │
   └──────────┘       └──────────┘       └──────────┘       └──────────┘
```

### 1. Portada educativa

**`GET /`** — Explica el flujo de transferencia de archivos, protocolos (FTP, SFTP, HTTP Upload), validaciones del lado servidor y enlaces al demo.

### 2. Registrarse o iniciar sesión

**`GET /demo/auth`** — Interfaz para:

| Acción | Método | Endpoint | Body |
|--------|--------|----------|------|
| **Registrarse** | `POST` | `/api/register` | `{ name, email, password, password_confirmation }` |
| **Iniciar sesión** | `POST` | `/api/login` | `{ email, password }` |

Ambos devuelven un **token de API** (Sanctum). Cópialo.

### 3. Demo interactivo de upload

**`GET /demo/upload`** — Pega el token y sube una imagen:

1. Arrastra una imagen o haz clic para seleccionar
2. El demo valida: tipo MIME, tamaño, dimensiones
3. Envía al servidor con el token en el header
4. Muestra la respuesta: URL, checksum SHA-256

---

## 📡 API REST

### Endpoints públicos

| Método | Ruta | Descripción |
|--------|------|-------------|
| `POST` | `/api/register` | Crear cuenta (name, email, password, password_confirmation) |
| `POST` | `/api/login` | Iniciar sesión (email, password) → devuelve token |
| `GET` | `/api/avatar/{id}` | Obtener URL del avatar de un usuario |

### Endpoints protegidos (requieren `Authorization: Bearer {token}`)

| Método | Ruta | Descripción |
|--------|------|-------------|
| `POST` | `/api/avatar` | Subir avatar (multipart: campo `avatar`) |
| `DELETE` | `/api/avatar` | Eliminar avatar |
| `POST` | `/api/logout` | Cerrar sesión (elimina token) |
| `GET` | `/api/user` | Datos del usuario autenticado |

### Probar con Postman

Importa la colección desde [`postman/EVA_WEB.postman_collection.json`](postman/EVA_WEB.postman_collection.json)

**Variables de colección:**

| Variable | Valor | Descripción |
|----------|-------|-------------|
| `base_url` | `http://eva-web.test` | URL del servidor |
| `token` | *(se llena automáticamente)* | Token de API |

**Flujo recomendado — ejecutar en orden:**

#### Paso 1 — Registrarse
```
POST {{base_url}}/api/register
Content-Type: application/json

{
    "name": "Juan Perez",
    "email": "juan@test.com",
    "password": "123456",
    "password_confirmation": "123456"
}
```
> ✅ Respuesta: `201` — devuelve el usuario creado + token

#### Paso 2 — Iniciar sesión
```
POST {{base_url}}/api/login
Content-Type: application/json

{
    "email": "juan@test.com",
    "password": "123456"
}
```
> ✅ Respuesta: `200` — el **script guarda el token automáticamente** en `{{token}}`
> 
> A partir de acá las requests protegidas usarán `Authorization: Bearer {{token}}`

#### Paso 3 — Subir avatar
```
POST {{base_url}}/api/avatar
Authorization: Bearer {{token}}
Content-Type: multipart/form-data

Body → form-data:
    avatar: (File) foto.jpg
```
> ✅ Respuesta: `201`
> ```json
> {
>   "message": "Avatar actualizado correctamente",
>   "data": {
>     "url": "/storage/avatars/uuid.jpg",
>     "tamano": 512000,
>     "mime": "image/jpeg",
>     "checksum": "a3f2b8c1..."
>   }
> }
> ```

#### Paso 4 — Consultar avatar
```
GET {{base_url}}/api/avatar/1
```
> ✅ Respuesta: `200` con URL del avatar
> ❌ Respuesta si no existe: `404`

#### Paso 5 — Eliminar avatar
```
DELETE {{base_url}}/api/avatar
Authorization: Bearer {{token}}
```
> ✅ Respuesta: `200` — `{"message": "Avatar eliminado correctamente"}`

#### Paso 6 — Cerrar sesión
```
POST {{base_url}}/api/logout
Authorization: Bearer {{token}}
```
> ✅ Respuesta: `200`

---

## 📁 Estructura del proyecto

```
eva-web/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   └── AvatarController.php   ← CRUD de avatar
│   │   │   └── Auth/
│   │   │       └── ApiAuthController.php   ← Registro/Login/Logout
│   │   └── ...
│   └── Models/
│       └── User.php                        ← +HasApiTokens, +avatar_url
├── database/
│   └── migrations/
│       └── ...add_avatar_url_to_users.php  ← Migración avatar_url
├── resources/
│   └── views/
│       ├── welcome.blade.php               ← Portada educativa
│       └── demo/
│           ├── upload.blade.php             ← Demo interactivo
│           └── auth.blade.php               ← Login/Registro
├── routes/
│   ├── api.php                             ← Rutas API
│   └── web.php                             ← Rutas web
├── postman/
│   └── EVA_WEB.postman_collection.json     ← Colección Postman
├── storage/
│   └── app/public/avatars/                 ← Uploads de avatar
└── .env                                    ← Configuración local
```

---

## 🧪 Validaciones implementadas

| Validación | Dónde | Método |
|-----------|-------|--------|
| Tipo MIME real | Servidor (AvatarController) | `getimagesize()` + `mimes` rule |
| Tamaño máximo | Servidor | `max:2048` (2MB) |
| Dimensiones mín/máx | Servidor | Closure: 100×100 a 1024×1024 px |
| Integridad SHA-256 | Servidor | `hash_file('sha256', ...)` |
| Nombre seguro UUID | Servidor | `Str::uuid()` evita colisiones |
| Validación previa | Cliente (JS) | Tipo MIME + tamaño + dimensiones |

---

## 🔐 Seguridad

- **Autenticación:** Laravel Sanctum (tokens bearer)
- **Almacenamiento:** Nombres UUID (evita path traversal)
- **Cifrado:** Usar HTTPS en producción (TLS)
- **Autorización:** Políticas por usuario (cada quien su propio avatar)
- **Limpieza:** Se elimina avatar anterior al subir uno nuevo

---

## 📚 Recursos adicionales

| Recurso | Enlace |
|---------|--------|
| Presentación reveal.js | `presentacion-s10/index.html` |
| Demos PHP/Laravel/Postman | `presentacion-s10/demo/` |
| Documentación Laravel 11 | https://laravel.com/docs/11.x |
| Postman | https://learning.postman.com/ |
| OWASP File Upload | https://owasp.org/www-community/vulnerabilities/Unrestricted_File_Upload |
| Mailtrap (email testing) | https://mailtrap.io |

---

## 📝 Licencia

Proyecto educativo — IESTP Jorge Basadre — Administración de Plataformas y Servicios Web
