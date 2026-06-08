# 📝 Tarea Semana 11 — Evaluación de Transferencia de Archivos

**Curso:** Evaluación y Control de Servicios Web  
**Endpoint a probar:** API de avatar (`POST /api/avatar`, `GET /api/avatar/{id}`, `DELETE /api/avatar`)  
**Repo:** https://github.com/jhoanchq/eva-web  
**Puntaje total:** 10 puntos  

---

## 🎯 Objetivo

Aplicar los conceptos de la Semana 11 (casos de prueba, restricciones, integridad, logs) sobre el endpoint de avatar desarrollado en la Semana 10. Debes ejecutar una batería de pruebas contra el API, documentar los resultados, verificar integridad de archivos y analizar logs del servidor.

---

## 📋 Instrucciones

### Parte 1 — Preparación (1 pt)

1. Clona o actualiza el repositorio:
   ```bash
   git clone https://github.com/jhoanchq/eva-web.git
   cd eva-web
   composer install
   cp .env.example .env
   php artisan key:generate
   ```

2. Configura tu base de datos MySQL en `.env` y ejecuta:
   ```bash
   php artisan migrate:fresh --seed
   php artisan storage:link
   ```

3. Genera un token de API:
   ```bash
   php artisan tinker
   ```
   ```php
   $user = App\Models\User::first();
   echo $user->createToken('test')->plainTextToken;
   ```
   > Copia el token. Lo usarás en las peticiones que requieran autenticación.

4. Prepara los archivos de prueba:
   - `foto.jpg` (~500KB) — imagen válida
   - `img.png` (~1MB) — imagen válida
   - `virus.exe` (~10KB) — ejecutable (debe ser rechazado)
   - `grande.mp4` (~5MB) — archivo que excede el límite
   - `script.php` (~1KB) — script (debe ser rechazado)

5. Abre Postman y configura la variable `base_url = http://127.0.0.1:8000` (o la URL de tu servidor).

---

### Parte 2 — Ejecutar batería de pruebas (5 pts)

Ejecuta cada caso en Postman y completa la tabla con el código HTTP obtenido.

| # | Método | URL | Archivo / Body | Token | Esperado | Obtenido |
|---|--------|-----|----------------|-------|----------|----------|
| 1 | POST | `/api/avatar` | `foto.jpg` (500KB) | Sí | 201 | |
| 2 | POST | `/api/avatar` | `img.png` (1MB) | Sí | 201 | |
| 3 | POST | `/api/avatar` | `virus.exe` (10KB) | Sí | 415 | |
| 4 | POST | `/api/avatar` | `grande.mp4` (5MB) | Sí | 413 | |
| 5 | POST | `/api/avatar` | `foto.jpg` | **No** | 401 | |
| 6 | GET | `/api/avatar/1` | — | No | 200 | |
| 7 | GET | `/api/avatar/9999` | — | No | 404 | |
| 8 | POST | `/api/avatar` | `script.php` (1KB) | Sí | 415 | |
| **9** | **DELETE** | `/api/avatar` | — | **Sí** | **200** | |
| **10** | **DELETE** | `/api/avatar` | — | **No** | **401** | |

> **Nota para el caso 9:** Primero sube un avatar con el caso 1, luego ejecuta DELETE con el mismo token. Después del DELETE, verifica con GET /api/avatar/1 que el avatar ya no existe.

---

### Parte 3 — Verificar integridad SHA-256 (2 pts)

1. Calcula el hash SHA-256 del archivo `foto.jpg` **antes** de subirlo:
   ```bash
   # Windows (PowerShell)
   Get-FileHash foto.jpg -Algorithm SHA256

   # Windows (cmd)
   certutil -hashfile foto.jpg SHA256

   # Linux / Mac
   sha256sum foto.jpg
   ```

2. Sube el archivo con el caso 1 de la Parte 2.

3. El servidor responde con la URL del avatar. Descarga el archivo desde esa URL.

4. Calcula el hash SHA-256 del archivo **descargado**.

5. Compara ambos hashes. ¿Coinciden? Anota el resultado en tu informe.

6. Repite para `img.png`.

| Archivo | Hash original | Hash descargado | ¿Coincide? |
|---------|---------------|-----------------|------------|
| `foto.jpg` | | | |
| `img.png` | | | |

---

### Parte 4 — Analizar logs del servidor (1 pt)

Revisa el archivo `storage/logs/laravel.log` del proyecto y busca las entradas generadas durante tus pruebas. Copia al menos **3 líneas relevantes** e indica:

- Timestamp exacto
- Nivel (INFO, ERROR, WARNING)
- Mensaje del log

| Timestamp | Nivel | Mensaje |
|-----------|-------|---------|
| | | |
| | | |
| | | |

---

### Parte 5 — Conclusión (1 pt)

Responde brevemente:

1. ¿El endpoint de avatar funciona según lo esperado para todos los casos?
2. Si encontraste algún error inesperado, ¿cuál fue y cómo lo documentarías como incidencia?
3. ¿Cómo crees que contribuye el monitoreo de logs a la detección temprana de problemas en servicios de transferencia de archivos?

---

## 📤 Entrega

- Descarga la **Plantilla_Pruebas.docx** desde el siguiente enlace y complétala:  
  🔗 https://presentaciones.apsw.jcspe.com/ecsw/semana11/Plantilla_Pruebas.docx
- Incluye capturas de pantalla de Postman como evidencia (al menos 3)
- Sube el documento completado a **Classroom** o entrégalo impreso según indicación del docente

---

## 📊 Rúbrica de evaluación

| Criterio | En inicio (<13) | En proceso (13-15) | Logrado (16-18) | Destacado (19-20) |
|----------|----------------|-------------------|-----------------|-------------------|
| Ejecución de pruebas | Menos de 6 casos | 6-8 casos | 9-10 casos | 10 casos + verificación extra |
| Registro de incidencias | No registra | Registra 1 | Registra 2+ | Registra 2+ con evidencia |
| Verificación integridad | No verifica | 1 archivo | 2 archivos | 2+ archivos con explicación |
| Análisis de logs | No revisa | 1-2 entradas | 3 entradas | 3+ entradas con interpretación |
| Conclusión | No responde | Responde 1 | Responde 2 | Responde 3 con profundidad |

---

*Evaluación y Control de Servicios Web — 2026-I*  
*Docente: Jhoan Benito Chite Quispe*
