<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Demo — Login / Registro</title>
<style>
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
.card {
    background: #0f1524;
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 20px;
    padding: 2rem;
    width: 100%;
    max-width: 440px;
}
.tabs {
    display: flex;
    gap: 0;
    margin-bottom: 1.5em;
    border-bottom: 1px solid rgba(255,255,255,0.06);
}
.tab {
    flex: 1;
    text-align: center;
    padding: 0.7em;
    cursor: pointer;
    color: #484f58;
    font-weight: 600;
    font-size: 0.85rem;
    border-bottom: 2px solid transparent;
    transition: all 0.2s;
    background: none;
    border-top: none; border-left: none; border-right: none;
}
.tab:hover { color: #8b949e; }
.tab.active { color: #00d4ff; border-bottom-color: #00d4ff; }
.form-container { display: none; }
.form-container.active { display: block; }
.form-group { margin-bottom: 1em; }
.form-group label {
    display: block;
    font-size: 0.75rem;
    color: #8b949e;
    margin-bottom: 0.3em;
}
.form-group input {
    width: 100%;
    padding: 0.7em;
    background: #0a0e1a;
    border: 1px solid #30363d;
    border-radius: 8px;
    color: #e6edf3;
    font-size: 0.85rem;
    transition: border-color 0.2s;
}
.form-group input:focus {
    outline: none;
    border-color: #00d4ff;
}
.btn {
    width: 100%;
    padding: 0.7em;
    background: #00d4ff;
    border: none;
    border-radius: 8px;
    color: #0a0e1a;
    font-weight: 700;
    font-size: 0.9rem;
    cursor: pointer;
    transition: background 0.2s;
    margin-top: 0.3em;
}
.btn:hover { background: #00b8d4; }
.btn:disabled { opacity: 0.5; cursor: not-allowed; }
.msg {
    display: none;
    padding: 0.7em;
    border-radius: 8px;
    font-size: 0.75rem;
    margin-top: 0.8em;
}
.msg.show { display: block; }
.msg.ok { background: rgba(0,200,100,0.12); border: 1px solid #00c864; color: #00c864; }
.msg.err { background: rgba(255,50,50,0.12); border: 1px solid #ff3232; color: #ff3232; }
.msg code {
    display: block;
    word-break: break-all;
    background: rgba(0,0,0,0.3);
    padding: 0.5em;
    border-radius: 4px;
    margin-top: 0.3em;
    font-size: 0.85rem;
}
.back-link {
    display: inline-block;
    color: #484f58;
    font-size: 0.7rem;
    text-decoration: none;
    margin-top: 1em;
    transition: color 0.2s;
}
.back-link:hover { color: #8b949e; }
</style>
</head>
<body>
<div class="card">
    <h1 style="font-size:1.2rem; margin-bottom:0.2em;">🔐 Autenticación</h1>
    <p style="color:#8b949e; font-size:0.75rem; margin-bottom:1em;">Regístrate o inicia sesión para obtener tu token de API</p>

    <!-- Pestañas Login / Register -->
    <div class="tabs">
        <button class="tab active" data-tab="login" onclick="switchTab('login')">Iniciar sesión</button>
        <button class="tab" data-tab="register" onclick="switchTab('register')">Registrarse</button>
    </div>

    <!-- FORM LOGIN -->
    <div class="form-container active" id="form-login">
        <div class="form-group">
            <label>Correo electrónico</label>
            <input type="email" id="login-email" placeholder="juan@ejemplo.com">
        </div>
        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" id="login-pass" placeholder="••••••••">
        </div>
        <button class="btn" onclick="login()">Ingresar</button>
        <div class="msg" id="msg-login"></div>
    </div>

    <!-- FORM REGISTRO -->
    <div class="form-container" id="form-register">
        <div class="form-group">
            <label>Nombre completo</label>
            <input type="text" id="reg-name" placeholder="Juan Perez">
        </div>
        <div class="form-group">
            <label>Correo electrónico</label>
            <input type="email" id="reg-email" placeholder="juan@ejemplo.com">
        </div>
        <div class="form-group">
            <label>Contraseña (mín 6 caracteres)</label>
            <input type="password" id="reg-pass" placeholder="••••••••">
        </div>
        <div class="form-group">
            <label>Confirmar contraseña</label>
            <input type="password" id="reg-pass-confirm" placeholder="••••••••">
        </div>
        <button class="btn" onclick="register()">Crear cuenta</button>
        <div class="msg" id="msg-register"></div>
    </div>

    <div style="text-align:center;">
        <a href="/demo/upload" class="back-link">← Ir al demo de upload</a>
    </div>
</div>

<script>
// Cambiar entre pestañas login/register
function switchTab(tab) {
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.form-container').forEach(f => f.classList.remove('active'));
    document.querySelector(`[data-tab="${tab}"]`).classList.add('active');
    document.getElementById('form-' + tab).classList.add('active');
}

// Mostrar mensaje en un contenedor
function showMsg(id, text, type) {
    const el = document.getElementById(id);
    el.className = 'msg show ' + type;
    el.innerHTML = text;
}

// === LOGIN ===
async function login() {
    const email = document.getElementById('login-email').value.trim();
    const password = document.getElementById('login-pass').value;
    if (!email || !password) {
        return showMsg('msg-login', 'Completa todos los campos', 'err');
    }
    try {
        const res = await fetch('/api/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ email, password })
        });
        const data = await res.json();
        if (res.ok) {
            showMsg('msg-login',
                '✅ Sesión iniciada. Tu token:' +
                `<code>${data.token}</code>` +
                '<span style="display:block;margin-top:0.3em;font-size:0.7rem;">Copia este token y pégalo en Postman o en el demo</span>',
                'ok');
        } else {
            showMsg('msg-login', '❌ ' + (data.message || data.errors?.email?.[0] || 'Error'), 'err');
        }
    } catch (e) {
        showMsg('msg-login', '❌ Error de conexión', 'err');
    }
}

// === REGISTRO ===
async function register() {
    const name = document.getElementById('reg-name').value.trim();
    const email = document.getElementById('reg-email').value.trim();
    const password = document.getElementById('reg-pass').value;
    const confirm = document.getElementById('reg-pass-confirm').value;

    if (!name || !email || !password) {
        return showMsg('msg-register', 'Completa todos los campos', 'err');
    }
    if (password.length < 6) {
        return showMsg('msg-register', 'La contraseña debe tener al menos 6 caracteres', 'err');
    }
    if (password !== confirm) {
        return showMsg('msg-register', 'Las contraseñas no coinciden', 'err');
    }
    try {
        const res = await fetch('/api/register', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ name, email, password, password_confirmation: confirm })
        });
        const data = await res.json();
        if (res.ok) {
            showMsg('msg-register',
                '✅ Cuenta creada. Tu token:' +
                `<code>${data.token}</code>` +
                '<span style="display:block;margin-top:0.3em;font-size:0.7rem;">Guarda este token — lo usarás para subir archivos</span>',
                'ok');
        } else {
            const errors = data.errors ? Object.values(data.errors).flat().join(', ') : '';
            showMsg('msg-register', '❌ ' + (data.message || errors || 'Error al registrarse'), 'err');
        }
    } catch (e) {
        showMsg('msg-register', '❌ Error de conexión', 'err');
    }
}
</script>
</body>
</html>
