<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Demo — Upload interactivo</title>
<style>
/* ========================================
   ESTILOS GENERALES
   ======================================== */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: 'Segoe UI', system-ui, sans-serif;
    background: #0a0e1a;
    color: #e6edf3;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 1rem;
}

/* ========================================
   TARJETA PRINCIPAL
   ======================================== */
.card {
    background: #0f1524;
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 20px;
    padding: 2rem;
    width: 100%;
    max-width: 640px;
}
/* Encabezado */
.card-header {
    display: flex;
    align-items: center;
    gap: 0.8em;
    margin-bottom: 0.3em;
}
.card-header h1 { font-size: 1.3rem; font-weight: 700; }
.card-header .tag {
    font-size: 0.6rem;
    padding: 0.2em 0.7em;
    border-radius: 10px;
    background: rgba(0,212,255,0.12);
    color: #00d4ff;
    font-weight: 600;
}
.card-desc { color: #8b949e; font-size: 0.8rem; margin-bottom: 1.5em; }

/* ========================================
   INDICADOR DE PASOS
   ======================================== */
.steps {
    display: flex;
    gap: 0.3em;
    margin-bottom: 1.5em;
}
.step {
    flex: 1;
    text-align: center;
    padding: 0.5em 0.3em;
    font-size: 0.6rem;
    border-radius: 8px;
    color: #484f58;
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.05);
    transition: all 0.3s;
}
/* Cada paso puede tener 3 estados */
.step.active { background: rgba(0,212,255,0.12); border-color: #00d4ff; color: #00d4ff; }
.step.done { background: rgba(0,200,100,0.12); border-color: #00c864; color: #00c864; }
.step.error { background: rgba(255,50,50,0.12); border-color: #ff3232; color: #ff3232; }

/* ========================================
   VISTA PREVIA DEL AVATAR
   ======================================== */
.avatar-area {
    display: flex;
    align-items: center;
    gap: 1.2em;
    margin-bottom: 1.2em;
    padding-bottom: 1.2em;
    border-bottom: 1px solid rgba(255,255,255,0.05);
}
.avatar-preview {
    width: 80px; height: 80px;
    border-radius: 50%;
    background: #0a0e1a;
    border: 2px dashed #30363d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2em;
    flex-shrink: 0;
    overflow: hidden;
    transition: border-color 0.3s;
}
/* Cuando el usuario ya seleccionó imagen */
.avatar-preview.loaded { border-color: #00d4ff; border-style: solid; }
.avatar-preview img { width: 100%; height: 100%; object-fit: cover; }
.avatar-info h3 { font-size: 0.9rem; }
.avatar-info p { color: #8b949e; font-size: 0.7rem; }

/* ========================================
   ZONA DE DROP / SELECTOR DE ARCHIVOS
   ======================================== */
.drop-zone {
    border: 2px dashed #30363d;
    border-radius: 12px;
    padding: 1.8em 1em;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    margin-bottom: 1em;
    position: relative;
}
/* Estado hover y drag-over */
.drop-zone:hover,
.drop-zone.dragover {
    border-color: #00d4ff;
    background: rgba(0,212,255,0.05);
}
.drop-zone .icon { font-size: 2.2em; display: block; margin-bottom: 0.3em; }
.drop-zone p { color: #8b949e; font-size: 0.8rem; }
.drop-zone p strong { color: #e6edf3; }
.drop-zone .hint { font-size: 0.65rem; color: #484f58; margin-top: 0.3em; }
/* Input file oculto — usamos el label area como target de click */
.drop-zone input[type="file"] { display: none; }

/* ========================================
   LOG DE EVENTOS
   ======================================== */
.log {
    background: #0a0e1a;
    border-radius: 10px;
    padding: 0.7em;
    font-family: 'Cascadia Code', 'Consolas', 'Courier New', monospace;
    font-size: 0.65rem;
    line-height: 1.7;
    max-height: 160px;
    overflow-y: auto;
    display: none; /* Se muestra solo cuando hay eventos */
    margin-bottom: 0.8em;
}
/* Estado visible del log */
.log.active { display: block; }

/* Colores para cada tipo de mensaje en el log */
.log .ok    { color: #00c864; }
.log .err   { color: #ff3232; }
.log .info  { color: #8b949e; }
.log .cyan  { color: #00d4ff; }

/* ========================================
   INPUT DE RESULTADO (URL del archivo subido)
   ======================================== */
.result-url {
    width: 100%;
    background: #0a0e1a;
    border: 1px solid #30363d;
    border-radius: 6px;
    padding: 0.5em 0.7em;
    color: #00c864;
    font-family: 'Cascadia Code', 'Consolas', monospace;
    font-size: 0.7rem;
    display: none;
    margin-bottom: 0.5em;
}
.result-url.active { display: block; }

/* ========================================
   ENLACE DE VOLVER
   ======================================== */
.back-link {
    display: inline-block;
    color: #484f58;
    font-size: 0.7rem;
    text-decoration: none;
    margin-top: 0.3em;
    transition: color 0.2s;
}
.back-link:hover { color: #8b949e; }

/* ========================================
   ANIMACIONES
   ======================================== */
/* Animación shake para errores */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    20% { transform: translateX(-6px); }
    40% { transform: translateX(6px); }
    60% { transform: translateX(-4px); }
    80% { transform: translateX(4px); }
}
.shake { animation: shake 0.4s ease; }

/* ========================================
   SCROLLBAR DEL LOG
   ======================================== */
.log::-webkit-scrollbar { width: 4px; }
.log::-webkit-scrollbar-track { background: transparent; }
.log::-webkit-scrollbar-thumb { background: #30363d; border-radius: 2px; }
</style>
</head>
<body>

<div class="card">
    <!-- Encabezado -->
    <div class="card-header">
        <span class="tag">Demo</span>
        <h1>📤 Subir avatar</h1>
    </div>
    <p class="card-desc">
        Selecciona una imagen y observa cómo el servidor la valida paso a paso.
    </p>

    <!-- ==========================================
         INDICADOR DE PASOS
         Muestra en qué etapa del proceso estamos:
         1. Seleccionar archivo
         2. Validar tipo y tamaño
         3. Validar dimensiones
         4. Subir al servidor
         5. Resultado final
    <!-- ========================================== -->
    <div class="steps">
        <div class="step active" id="s1">1. Seleccionar</div>
        <div class="step" id="s2">2. Validar</div>
        <div class="step" id="s3">3. Dimensiones</div>
        <div class="step" id="s4">4. Subir</div>
        <div class="step" id="s5">5. Resultado</div>
    </div>

    <!-- ==========================================
         ÁREA DEL AVATAR
         Muestra la imagen previa y nombre de usuario
    <!-- ========================================== -->
    <div class="avatar-area">
        <div class="avatar-preview" id="preview">📷</div>
        <div class="avatar-info">
            <h3>👤 juan_perez</h3>
            <p id="status-text">Sin avatar — selecciona una imagen</p>
        </div>
    </div>

    <!-- ==========================================
         ZONA DE DROP
         Permite arrastrar o hacer clic para seleccionar
    <!-- ========================================== -->
    <div class="drop-zone" id="dropzone">
        <span class="icon">📤</span>
        <p>Arrastra una imagen aquí o <strong>haz clic</strong> para seleccionar</p>
        <p class="hint">JPG, PNG o WEBP · Máx 2MB · Mín 100×100px</p>
        <input type="file" id="file-input" accept="image/jpeg,image/png,image/webp">
    </div>

    <!-- ==========================================
         INPUT DE TOKEN (opcional)
         Si el usuario tiene un token, lo pega aquí
         para autenticarse en la API
    <!-- ========================================== -->
    <div style="margin-bottom:0.8em;">
        <label style="font-size:0.7rem; color:#8b949e; display:block; margin-bottom:0.2em;">
            🔑 Token de API 
            <span style="color:#484f58;">(obtén uno en </span>
            <a href="/demo/auth" style="color:#00d4ff;">/demo/auth</a>
            <span style="color:#484f58;">)</span>
        </label>
        <input type="text" id="token-input" placeholder="Pega tu token aquí..." style="width:100%; padding:0.5em; background:#0a0e1a; border:1px solid #30363d; border-radius:6px; color:#e6edf3; font-size:0.7rem; font-family:'Cascadia Code', monospace;">
    </div>

    <!-- ==========================================
         LOG DE VALIDACIÓN
         Cada mensaje se añade aquí con formato coloreado
    <!-- ========================================== -->
    <div class="log" id="log"></div>

    <!-- ==========================================
         RESULTADO
         Se muestra la URL del archivo subido
    <!-- ========================================== -->
    <input class="result-url" id="result-url" readonly placeholder="URL del archivo subido...">

    <!-- ==========================================
         ENLACE PARA VOLVER A LA PORTADA
    <!-- ========================================== -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:0.3em;">
        <a href="/" class="back-link">← Volver a la presentación</a>
        <a href="/demo/auth" class="back-link" style="color:#00d4ff;">🔑 Obtener token →</a>
    </div>
</div>

<!-- ============================================================
     JAVASCRIPT DEL DEMO
     ============================================================
     Funcionamiento:
     1. El usuario arrastra o selecciona un archivo
     2. Se validan tipo MIME, tamaño y dimensiones (cliente)
     3. Se envía al servidor mediante fetch()
     4. Cada etapa actualiza el indicador de pasos y el log
     ============================================================ -->
<script>
// ==========================================
// REFERENCIAS A ELEMENTOS DEL DOM
// ==========================================
const dropzone   = document.getElementById('dropzone');
const fileInput  = document.getElementById('file-input');
const preview    = document.getElementById('preview');
const statusText = document.getElementById('status-text');
const log        = document.getElementById('log');
const resultUrl  = document.getElementById('result-url');

// ==========================================
// FUNCIÓN: Reiniciar todos los pasos al estado inicial
// ==========================================
function resetSteps() {
    document.querySelectorAll('.step').forEach(s => {
        s.className = 'step'; // Quita active, done, error
    });
    document.getElementById('s1').classList.add('active'); // Paso 1 activo
}

// ==========================================
// FUNCIÓN: Marcar un paso como completado y activar el siguiente
// @param {number} num - Número del paso a marcar como completado
// ==========================================
function setStepDone(num) {
    const step = document.getElementById('s' + num);
    step.classList.remove('active');
    step.classList.add('done');
    const next = document.getElementById('s' + (num + 1));
    if (next) next.classList.add('active');
}

// ==========================================
// FUNCIÓN: Marcar un paso como error
// @param {number} num - Número del paso en error
// ==========================================
function setStepError(num) {
    const step = document.getElementById('s' + num);
    step.classList.remove('active');
    step.classList.add('error');
}

// ==========================================
// FUNCIÓN: Escribir un mensaje en el log
// @param {string} msg - Mensaje a mostrar
// @param {string} type - Tipo: 'ok' (verde), 'err' (rojo), 'info' (gris)
// ==========================================
function writeLog(msg, type = 'info') {
    log.classList.add('active');
    log.innerHTML += `<div class="${type}">${msg}</div>`;
    log.scrollTop = log.scrollHeight; // Auto-scroll al último mensaje
}

// ==========================================
// FUNCIÓN: Validar tipo MIME y tamaño del archivo
// @param {File} file - Archivo seleccionado por el usuario
// @returns {boolean} - true si pasa las validaciones
// ==========================================
function validarArchivo(file) {
    // Lista de tipos MIME permitidos
    const tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp'];
    const maxSize = 2 * 1024 * 1024; // 2MB en bytes

    // Validar tipo MIME
    if (!tiposPermitidos.includes(file.type)) {
        writeLog(`❌ Tipo no permitido: ${file.type || 'desconocido'}`, 'err');
        setStepError(2);
        return false;
    }
    writeLog(`✅ Tipo MIME: <span class="cyan">${file.type}</span>`, 'ok');

    // Validar tamaño
    if (file.size > maxSize) {
        writeLog(`❌ Tamaño excedido: ${(file.size / 1024 / 1024).toFixed(2)}MB (máx 2MB)`, 'err');
        setStepError(2);
        return false;
    }
    writeLog(`✅ Tamaño: <span class="cyan">${(file.size / 1024).toFixed(1)} KB</span>`, 'ok');

    return true;
}

// ==========================================
// FUNCIÓN: Validar dimensiones de la imagen
// @param {File} file - Archivo de imagen
// @returns {Promise<boolean>} - Promise que resuelve a true si pasa
// ==========================================
function validarDimensiones(file) {
    return new Promise((resolve) => {
        const img = new Image();
        const url = URL.createObjectURL(file);

        img.onload = () => {
            URL.revokeObjectURL(url); // Limpiar memoria
            const minDim = 100;
            if (img.width < minDim || img.height < minDim) {
                writeLog(`❌ Dimensiones muy pequeñas: ${img.width}×${img.height}px (mín ${minDim}×${minDim})`, 'err');
                setStepError(3);
                resolve(false);
            } else {
                writeLog(`✅ Dimensiones: <span class="cyan">${img.width}×${img.height}px</span>`, 'ok');
                resolve(true);
            }
        };

        img.onerror = () => {
            URL.revokeObjectURL(url);
            writeLog('❌ No se pudo leer la imagen', 'err');
            setStepError(3);
            resolve(false);
        };

        img.src = url;
    });
}

// ==========================================
// FUNCIÓN: Enviar el archivo al servidor via fetch()
// @param {File} file - Archivo a subir
// @returns {Promise<boolean>} - true si la subida fue exitosa
// ==========================================
async function subirArchivo(file) {
    writeLog('📤 Enviando archivo al servidor...', 'info');

    // Construir FormData con el archivo
    const formData = new FormData();
    formData.append('avatar', file);

    try {
        // Obtener token del input (si el usuario lo pegó)
        const token = document.getElementById('token-input').value.trim();

        // Construir headers con autenticación si hay token
        const headers = { 'Accept': 'application/json' };
        if (token) {
            headers['Authorization'] = 'Bearer ' + token;
            writeLog('🔑 Usando token: ' + token.substring(0, 20) + '...', 'info');
        } else {
            writeLog('⚠️ Sin token — la petición fallará si el endpoint requiere auth', 'info');
            writeLog('💡 Obtén un token en /demo/auth', 'info');
        }

        // Enviar petición POST al endpoint de avatar
        const res = await fetch('/api/avatar', {
            method: 'POST',
            body: formData,
            headers: headers
        });

        const data = await res.json();

        if (res.ok) {
            // Éxito: mostrar respuesta del servidor
            setStepDone(4);
            writeLog(`✅ Servidor respondió: ${res.status} ${res.statusText}`, 'ok');
            writeLog(`📎 URL: <span class="cyan">${data.data.url}</span>`, 'info');
            writeLog(`🔐 SHA-256: <span class="cyan">${data.data.checksum}</span>`, 'info');

            // Mostrar URL en el input de resultado
            resultUrl.value = window.location.origin + data.data.url;
            resultUrl.classList.add('active');

            setStepDone(5);
            statusText.textContent = '✅ Avatar actualizado correctamente';
            return true;
        } else {
            // Error del servidor
            let msg = data.message || JSON.stringify(data);
            if (res.status === 401) {
                msg = 'No autenticado. Obtén un token en /demo/auth';
            }
            writeLog(`❌ Error ${res.status}: ${msg}`, 'err');
            setStepError(4);
            return false;
        }
    } catch (e) {
        // Error de conexión (no hay servidor, modo demo)
        writeLog('⚠️ No hay conexión con el servidor. Modo demostración.', 'info');
        setStepDone(4);
        setStepDone(5);
        statusText.textContent = '✅ Avatar listo (demo local)';
        resultUrl.value = `uploads/${file.name}`;
        resultUrl.classList.add('active');
        return true;
    }
}

// ==========================================
// FUNCIÓN PRINCIPAL: Procesar el archivo completo
// Ejecuta la secuencia completa: validar → dimensiones → subir
// @param {File} file - Archivo seleccionado
// ==========================================
async function procesarArchivo(file) {
    // Limpiar estado anterior
    log.innerHTML = '';
    log.classList.remove('active');
    resultUrl.classList.remove('active');
    resetSteps();

    writeLog(`📁 Archivo seleccionado: <span class="cyan">${file.name}</span>`);

    // --- Paso 1: Seleccionar (ya está) ---
    setStepDone(1);

    // --- Paso 2: Validar tipo y tamaño ---
    if (!validarArchivo(file)) return;
    setStepDone(2);

    // --- Paso 3: Validar dimensiones ---
    const dimOk = await validarDimensiones(file);
    if (!dimOk) return;
    setStepDone(3);

    // --- Mostrar preview de la imagen ---
    const reader = new FileReader();
    reader.onload = (e) => {
        preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
        preview.classList.add('loaded');
    };
    reader.readAsDataURL(file);

    // --- Paso 4: Subir al servidor ---
    await subirArchivo(file);
}

// ==========================================
// EVENTOS DE INTERACCIÓN
// ==========================================

// Clic en la zona de drop → abre el selector de archivos
dropzone.addEventListener('click', () => fileInput.click());

// Efecto visual al arrastrar archivos sobre la zona
dropzone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropzone.classList.add('dragover');
});

dropzone.addEventListener('dragleave', () => {
    dropzone.classList.remove('dragover');
});

// Soltar archivo en la zona de drop
dropzone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone.classList.remove('dragover');
    const files = e.dataTransfer.files;
    if (files.length) procesarArchivo(files[0]);
});

// Seleccionar archivo mediante el input
fileInput.addEventListener('change', () => {
    if (fileInput.files.length) procesarArchivo(fileInput.files[0]);
});
</script>

</body>
</html>
