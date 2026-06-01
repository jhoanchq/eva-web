<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Transferencia de Archivos — Curso ECSW</title>
<style>
/* ========== ESTILOS GLOBALES ========== */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    background: #0a0e1a;
    color: #e6edf3;
    line-height: 1.6;
}
/* ========== HERO / PORTADA ========== */
.hero {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    padding: 2rem;
    background:
        radial-gradient(ellipse at 20% 50%, rgba(0,212,255,0.08) 0%, transparent 50%),
        radial-gradient(ellipse at 80% 50%, rgba(255,107,53,0.06) 0%, transparent 50%),
        #0a0e1a;
    position: relative;
    overflow: hidden;
}
.hero::before {
    content: '';
    position: absolute;
    width: 300px; height: 300px;
    border-radius: 50%;
    border: 1px solid rgba(0,212,255,0.1);
    top: -100px; right: -100px;
}
.hero h1 {
    font-size: clamp(2rem, 5vw, 3.5rem);
    font-weight: 800;
    background: linear-gradient(135deg, #00d4ff 0%, #ff6b35 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 0.3em;
}
.hero .tag {
    display: inline-block;
    padding: 0.3em 1em;
    border-radius: 20px;
    font-size: 0.8rem;
    background: rgba(0,212,255,0.12);
    color: #00d4ff;
    font-weight: 600;
    margin-bottom: 1em;
    letter-spacing: 0.05em;
}
.hero .sub {
    color: #8b949e;
    font-size: 1rem;
    max-width: 600px;
    margin-bottom: 1.5em;
}
.hero .btn {
    display: inline-block;
    padding: 0.8em 2em;
    background: #00d4ff;
    color: #0a0e1a;
    font-weight: 700;
    border-radius: 8px;
    text-decoration: none;
    transition: transform 0.2s, box-shadow 0.2s;
}
.hero .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,212,255,0.3);
}
/* ========== SECCIONES ========== */
section { padding: 4rem 2rem; max-width: 1100px; margin: 0 auto; }
.section-title {
    font-size: 1.6rem;
    font-weight: 700;
    margin-bottom: 0.3em;
    color: #fff;
}
.section-desc {
    color: #8b949e;
    font-size: 0.9rem;
    margin-bottom: 2em;
}
/* ========== DIAGRAMA DE FLUJO ========== */
.flow-container {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    flex-wrap: wrap;
    padding: 2rem 0;
}
.flow-step {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 12px;
    padding: 1.2em 1.5em;
    text-align: center;
    min-width: 120px;
    flex: 1;
    transition: border-color 0.3s;
}
.flow-step:hover { border-color: rgba(0,212,255,0.3); }
.flow-step .icon { font-size: 1.8em; display: block; margin-bottom: 0.3em; }
.flow-step .title { font-weight: 600; color: #fff; font-size: 0.85rem; }
.flow-step .desc { font-size: 0.7rem; color: #8b949e; margin-top: 0.2em; }
.flow-arrow {
    font-size: 1.5em;
    color: #00d4ff;
    flex-shrink: 0;
}
/* ========== GRID DE PROTOCOLOS ========== */
.protocol-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.2em;
}
.protocol-card {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 12px;
    padding: 1.5em;
    transition: transform 0.2s, border-color 0.2s;
}
.protocol-card:hover {
    transform: translateY(-3px);
    border-color: rgba(0,212,255,0.2);
}
.protocol-card h3 { font-size: 1rem; margin-bottom: 0.5em; color: #fff; }
.protocol-card .badge {
    display: inline-block;
    font-size: 0.65rem;
    padding: 0.2em 0.6em;
    border-radius: 10px;
    margin-bottom: 0.5em;
}
.protocol-card ul { list-style: none; padding: 0; }
.protocol-card li {
    font-size: 0.8rem;
    color: #8b949e;
    padding: 0.2em 0;
    padding-left: 1.2em;
    position: relative;
}
.protocol-card li::before {
    content: '›';
    position: absolute;
    left: 0;
    color: #00d4ff;
    font-weight: 700;
}
/* ========== COMPARATIVA ========== */
.comparison {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1em;
    margin-top: 1em;
}
.comparison-col {
    background: rgba(255,255,255,0.03);
    border-radius: 12px;
    padding: 1.2em;
    border: 1px solid rgba(255,255,255,0.06);
}
.comparison-col h4 { color: #fff; font-size: 0.9rem; margin-bottom: 0.8em; }
.comparison-col table { width: 100%; font-size: 0.8rem; border-collapse: collapse; }
.comparison-col td { padding: 0.4em 0; border-bottom: 1px solid rgba(255,255,255,0.05); }
.comparison-col td:last-child { text-align: right; }
/* ========== PASOS DE VALIDACIÓN ========== */
.steps-list { counter-reset: step; }
.step-item {
    display: flex;
    gap: 1em;
    padding: 0.8em 0;
    border-bottom: 1px solid rgba(255,255,255,0.05);
}
.step-item:last-child { border-bottom: none; }
.step-num {
    width: 32px; height: 32px;
    border-radius: 50%;
    background: rgba(0,212,255,0.12);
    color: #00d4ff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.8rem;
    flex-shrink: 0;
}
.step-content h4 { color: #fff; font-size: 0.9rem; }
.step-content p { color: #8b949e; font-size: 0.8rem; }
/* ========== CTA FINAL ========== */
.cta-section {
    text-align: center;
    padding: 3rem 2rem;
    border-top: 1px solid rgba(255,255,255,0.06);
}
.cta-section h2 { font-size: 1.4rem; margin-bottom: 0.5em; }
.cta-section .btn-group { display: flex; gap: 1em; justify-content: center; flex-wrap: wrap; margin-top: 1em; }
.cta-section .btn-secondary {
    padding: 0.7em 1.5em;
    border: 1px solid rgba(0,212,255,0.3);
    border-radius: 8px;
    color: #00d4ff;
    text-decoration: none;
    font-weight: 600;
    transition: background 0.2s;
}
.cta-section .btn-secondary:hover { background: rgba(0,212,255,0.1); }
/* ========== FOOTER ========== */
.footer {
    text-align: center;
    padding: 2rem;
    color: #484f58;
    font-size: 0.75rem;
    border-top: 1px solid rgba(255,255,255,0.04);
}
</style>
</head>
<body>

<!-- ===== HERO ===== -->
<header class="hero">
    <span class="tag">Semana 10 — Evaluación y Control de Servicios Web</span>
    <h1>Servicios de<br>Transferencia de Archivos</h1>
    <p class="sub">
        Aprende cómo funcionan los protocolos FTP, SFTP y HTTP Upload,
        y cómo validar, asegurar y procesar archivos del lado del servidor.
    </p>
        <a href="/demo/auth" class="btn" style="background:transparent; border:1px solid #00d4ff; color:#00d4ff; margin-right:0.5rem;">🔑 Obtener token</a>
        <a href="/demo/upload" class="btn">🚀 Probar demo interactiva</a>
</header>

<!-- ===== DIAGRAMA DE FLUJO ===== -->
<section>
    <h2 class="section-title">📊 Flujo de una transferencia</h2>
    <p class="section-desc">Cada vez que subes un archivo, los datos viajan del cliente al servidor pasando por varias etapas.</p>
    <div class="flow-container">
        <div class="flow-step">
            <span class="icon">👤</span>
            <span class="title">Cliente</span>
            <span class="desc">Navegador o app</span>
        </div>
        <span class="flow-arrow">→</span>
        <div class="flow-step">
            <span class="icon">📦</span>
            <span class="title">Petición HTTP</span>
            <span class="desc">multipart/form-data</span>
        </div>
        <span class="flow-arrow">→</span>
        <div class="flow-step">
            <span class="icon">🌐</span>
            <span class="title">Internet</span>
            <span class="desc">Cifrado TLS</span>
        </div>
        <span class="flow-arrow">→</span>
        <div class="flow-step">
            <span class="icon">🖥️</span>
            <span class="title">Servidor</span>
            <span class="desc">Valida + almacena</span>
        </div>
        <span class="flow-arrow">→</span>
        <div class="flow-step">
            <span class="icon">✅</span>
            <span class="title">Respuesta</span>
            <span class="desc">201 / error</span>
        </div>
    </div>
</section>

<!-- ===== PROTOCOLOS ===== -->
<section>
    <h2 class="section-title">📡 Protocolos de transferencia</h2>
    <p class="section-desc">Conoce los protocolos más usados para enviar archivos entre sistemas.</p>
    <div class="protocol-grid">
        <div class="protocol-card">
            <span class="badge" style="background: rgba(255,107,53,0.15); color: #ff6b35;">FTP</span>
            <h3>File Transfer Protocol</h3>
            <ul>
                <li>Puertos 21 (control) y 20 (datos)</li>
                <li>Transmite en texto plano</li>
                <li>Sin cifrado — inseguro en internet</li>
                <li>Usar solo en redes internas</li>
            </ul>
        </div>
        <div class="protocol-card">
            <span class="badge" style="background: rgba(0,212,255,0.15); color: #00d4ff;">SFTP</span>
            <h3>SSH File Transfer Protocol</h3>
            <ul>
                <li>Puerto 22 — sobre SSH</li>
                <li>Todo el tráfico cifrado</li>
                <li>Autenticación por clave SSH</li>
                <li>Recomendado para producción</li>
            </ul>
        </div>
        <div class="protocol-card">
            <span class="badge" style="background: rgba(0,200,100,0.15); color: #00c864;">HTTP Upload</span>
            <h3>Multipart / form-data</h3>
            <ul>
                <li>Método POST con Content-Type especial</li>
                <li>Usa boundaries para separar campos</li>
                <li>Ideal para APIs REST</li>
                <li>Soportado por navegadores y Postman</li>
            </ul>
        </div>
    </div>
</section>

<!-- ===== FTP vs SFTP ===== -->
<section>
    <h2 class="section-title">⚔️ Comparativa: FTP vs SFTP</h2>
    <div class="comparison">
        <div class="comparison-col" style="border-color: rgba(255,107,53,0.2);">
            <h4 style="color: #ff6b35;">⚠️ FTP</h4>
            <table>
                <tr><td>Cifrado</td><td style="color:#ff6b35;">No</td></tr>
                <tr><td>Puertos</td><td>21 + 20</td></tr>
                <tr><td>Autenticación</td><td>Usuario/contraseña</td></tr>
                <tr><td>Firewall</td><td style="color:#ff6b35;">Problemático</td></tr>
                <tr><td>Integridad</td><td style="color:#ff6b35;">No nativa</td></tr>
            </table>
        </div>
        <div class="comparison-col" style="border-color: rgba(0,212,255,0.2);">
            <h4 style="color: #00d4ff;">✅ SFTP</h4>
            <table>
                <tr><td>Cifrado</td><td style="color:#00c864;">Sí (SSH)</td></tr>
                <tr><td>Puertos</td><td>Solo 22</td></tr>
                <tr><td>Autenticación</td><td>Clave SSH</td></tr>
                <tr><td>Firewall</td><td style="color:#00c864;">Amigable</td></tr>
                <tr><td>Integridad</td><td style="color:#00c864;">Garantizada</td></tr>
            </table>
        </div>
    </div>
</section>

<!-- ===== VALIDACIONES ===== -->
<section>
    <h2 class="section-title">🛡️ Validaciones del lado servidor</h2>
    <p class="section-desc">Nunca confíes en la validación del cliente — todo archivo debe validarse en el servidor.</p>
    <div class="steps-list">
        <div class="step-item">
            <div class="step-num">1</div>
            <div class="step-content">
                <h4>Validar tipo MIME real</h4>
                <p>Usar <code>finfo</code> o <code>getimagesize()</code> para检测 el tipo real del archivo, no solo la extensión.</p>
            </div>
        </div>
        <div class="step-item">
            <div class="step-num">2</div>
            <div class="step-content">
                <h4>Controlar tamaño máximo</h4>
                <p>Definir un límite (ej: 2MB) y validar con <code>$_FILES['file']['size']</code> o las reglas de Laravel.</p>
            </div>
        </div>
        <div class="step-item">
            <div class="step-num">3</div>
            <div class="step-content">
                <h4>Verificar integridad con hash</h4>
                <p>Calcular SHA-256 del archivo recibido y compararlo con el hash enviado por el cliente para detectar corrupción.</p>
            </div>
        </div>
        <div class="step-item">
            <div class="step-num">4</div>
            <div class="step-content">
                <h4>Almacenar con nombre seguro</h4>
                <p>Generar nombres únicos con UUID para evitar colisiones y ataques de path traversal.</p>
            </div>
        </div>
    </div>
</section>

<!-- ===== CTA ===== -->
<div class="cta-section">
    <h2>👉 Pruébalo tú mismo</h2>
    <p style="color:#8b949e; font-size:0.85rem;">Sube un archivo real y observa cada paso de la validación.</p>
    <div class="btn-group">
        <a href="/demo/auth" class="btn-secondary">🔑 Registrarse / Login</a>
        <a href="/demo/upload" class="btn" style="background:#00d4ff; color:#0a0e1a; padding:0.7em 1.8em; border-radius:8px; text-decoration:none; font-weight:700;">📤 Demo interactivo</a>
    </div>
</div>

<footer class="footer">
    Evaluación y Control de Servicios Web — 2026-I | Docente: Jhoan Benito Chite Quispe
</footer>

</body>
</html>
