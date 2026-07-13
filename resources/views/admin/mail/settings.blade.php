<x-app-layout>
    <x-slot name="header"><i class="fa-regular fa-envelope"></i> Configuración de correo SMTP</x-slot>

    <div class="main-card p-6 sm:p-8">
        @if(session('success'))
            <div class="p-3 mb-4 rounded-lg" style="background:rgba(0,200,100,0.12);border:1px solid #00c864;color:#00c864;font-size:0.85rem;display:flex;align-items:center;gap:0.5rem;">
                <i class="fa-regular fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-3 mb-4 rounded-lg" style="background:rgba(255,50,50,0.12);border:1px solid #ff5555;color:#ff5555;font-size:0.85rem;display:flex;align-items:center;gap:0.5rem;">
                <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
            </div>
        @endif

        <!-- Estado actual -->
        <div class="p-4 mb-6 rounded-lg" style="background:rgba(139,233,253,0.06);border:1px solid rgba(139,233,253,0.15);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:0.5rem;">
            <div style="display:flex;align-items:center;gap:0.8rem;">
                <div style="width:40px;height:40px;border-radius:50%;background:rgba(0,200,100,0.12);display:flex;align-items:center;justify-content:center;">
                    <i class="fa-regular fa-circle-check" style="color:#00c864;"></i>
                </div>
                <div>
                    <p style="color:#e6edf3;font-weight:600;font-size:0.85rem;margin:0;">Servidor SMTP configurado</p>
                    <p style="color:#6272a4;font-size:0.75rem;margin:0.1rem 0 0;">
                        {{ $settings->host ?: '—' }}:{{ $settings->port ?: '—' }}
                        <span style="margin:0 0.3rem;">·</span>
                        From: {{ $settings->from_address ?: '—' }}
                        @if($settings->username)
                        <span style="margin:0 0.3rem;">·</span>
                        <i class="fa-regular fa-circle-check" style="color:#50fa7b;font-size:0.7rem;"></i> Auth configurada
                        @endif
                    </p>
                </div>
            </div>
            <a href="{{ route('admin.mail.test') }}" class="auth-btn" style="font-size:0.75rem;padding:0.4rem 1rem;background:rgba(139,233,253,0.1);border:1px solid #8be9fd;color:#8be9fd;text-decoration:none;border-radius:6px;">
                <i class="fa-regular fa-paper-plane"></i> Probar envío
            </a>
        </div>

        <form method="POST" action="{{ route('admin.mail.settings.update') }}" id="mailForm">
            @csrf

            <!-- Servidor SMTP -->
            <div class="mb-6">
                <h3 style="color:#8be9fd;font-size:0.95rem;font-weight:600;margin:0 0 0.8rem 0;display:flex;align-items:center;gap:0.4rem;">
                    <i class="fa-solid fa-server" style="color:#8be9fd;"></i> Servidor SMTP
                </h3>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0.8rem;">
                    <div>
                        <label class="auth-label"><i class="fa-solid fa-cog" style="width:1.2em;color:#8be9fd;"></i> Mailer</label>
                        <select name="mailer" class="auth-input">
                            <option value="smtp" {{ $settings->mailer == 'smtp' ? 'selected' : '' }}>SMTP</option>
                            <option value="sendmail" {{ $settings->mailer == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                            <option value="log" {{ $settings->mailer == 'log' ? 'selected' : '' }}>Log (solo pruebas)</option>
                        </select>
                        @error('mailer')<div class="auth-error">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="auth-label"><i class="fa-solid fa-globe" style="width:1.2em;color:#8be9fd;"></i> Host</label>
                        <input type="text" name="host" class="auth-input" value="{{ old('host', $settings->host) }}" placeholder="sandbox.smtp.mailtrap.io">
                        @error('host')<div class="auth-error">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="auth-label"><i class="fa-solid fa-plug" style="width:1.2em;color:#8be9fd;"></i> Puerto</label>
                        <input type="number" name="port" class="auth-input" value="{{ old('port', $settings->port) }}" placeholder="2525">
                        @error('port')<div class="auth-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <!-- Autenticación -->
            <div class="mb-6">
                <h3 style="color:#8be9fd;font-size:0.95rem;font-weight:600;margin:0 0 0.8rem 0;display:flex;align-items:center;gap:0.4rem;">
                    <i class="fa-solid fa-lock" style="color:#8be9fd;"></i> Autenticación
                </h3>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0.8rem;">
                    <div>
                        <label class="auth-label"><i class="fa-regular fa-user" style="width:1.2em;color:#8be9fd;"></i> Usuario</label>
                        <input type="text" name="username" class="auth-input" value="{{ old('username', $settings->username) }}" placeholder="usuario@dominio.com">
                    </div>
                    <div>
                        <label class="auth-label"><i class="fa-solid fa-key" style="width:1.2em;color:#8be9fd;"></i> Contraseña</label>
                        <div style="position:relative;">
                            <input type="password" name="password" class="auth-input" id="smtp_password" placeholder="••••••••" style="padding-right:2.5rem;">
                            <button type="button" onclick="togglePassword()" style="position:absolute;right:0.5rem;top:50%;transform:translateY(-50%);background:none;border:none;color:#6272a4;cursor:pointer;font-size:0.85rem;">
                                <i class="fa-regular fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                        <p style="color:#484f58;font-size:0.65rem;margin-top:0.2rem;">Dejar vacío para mantener la actual.</p>
                    </div>
                    <div>
                        <label class="auth-label"><i class="fa-solid fa-shield" style="width:1.2em;color:#8be9fd;"></i> Encriptación</label>
                        <select name="encryption" class="auth-input">
                            <option value="tls" {{ $settings->encryption == 'tls' ? 'selected' : '' }}>TLS (recomendado)</option>
                            <option value="ssl" {{ $settings->encryption == 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="null" {{ $settings->encryption == 'null' ? 'selected' : '' }}>Ninguna</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Remitente -->
            <div class="mb-6">
                <h3 style="color:#8be9fd;font-size:0.95rem;font-weight:600;margin:0 0 0.8rem 0;display:flex;align-items:center;gap:0.4rem;">
                    <i class="fa-regular fa-envelope" style="color:#8be9fd;"></i> Remitente (From)
                </h3>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.8rem;">
                    <div>
                        <label class="auth-label">Correo electrónico</label>
                        <input type="email" name="from_address" class="auth-input" value="{{ old('from_address', $settings->from_address) }}" placeholder="noreply@eva-web.test">
                        @error('from_address')<div class="auth-error">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="auth-label">Nombre del remitente</label>
                        <input type="text" name="from_name" class="auth-input" value="{{ old('from_name', $settings->from_name) }}" placeholder="EVA-WEB">
                        @error('from_name')<div class="auth-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <!-- Puertos comunes -->
            <div class="mb-6 p-3 rounded-lg" style="background:rgba(255,184,108,0.06);border:1px solid rgba(255,184,108,0.15);">
                <p style="color:#ffb86c;font-size:0.75rem;font-weight:600;margin:0 0 0.3rem 0;"><i class="fa-solid fa-lightbulb"></i> Puertos SMTP comunes</p>
                <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                    <span onclick="setPort(25)" style="cursor:pointer;padding:0.15rem 0.5rem;border-radius:4px;background:rgba(255,255,255,0.05);color:#8b949e;font-size:0.7rem;border:1px solid rgba(255,255,255,0.08);">25 — SMTP sin cifrar</span>
                    <span onclick="setPort(587)" style="cursor:pointer;padding:0.15rem 0.5rem;border-radius:4px;background:rgba(0,212,255,0.1);color:#8be9fd;font-size:0.7rem;border:1px solid rgba(0,212,255,0.2);">587 — SMTP con TLS</span>
                    <span onclick="setPort(465)" style="cursor:pointer;padding:0.15rem 0.5rem;border-radius:4px;background:rgba(80,250,123,0.1);color:#50fa7b;font-size:0.7rem;border:1px solid rgba(80,250,123,0.2);">465 — SMTP con SSL</span>
                    <span onclick="setPort(2525)" style="cursor:pointer;padding:0.15rem 0.5rem;border-radius:4px;background:rgba(255,121,198,0.1);color:#ff79c6;font-size:0.7rem;border:1px solid rgba(255,121,198,0.2);">2525 — Alternativo Mailtrap</span>
                </div>
            </div>

            <!-- Proveedores -->
            <div class="mb-6 p-3 rounded-lg" style="background:rgba(139,233,253,0.04);border:1px solid rgba(139,233,253,0.1);">
                <p style="color:#8be9fd;font-size:0.75rem;font-weight:600;margin:0 0 0.3rem 0;"><i class="fa-solid fa-cloud"></i> Proveedores SMTP</p>
                <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                    <span onclick="fillMailtrap()" style="cursor:pointer;padding:0.15rem 0.5rem;border-radius:4px;background:rgba(139,233,253,0.1);color:#8be9fd;font-size:0.7rem;border:1px solid rgba(139,233,253,0.2);"><i class="fa-regular fa-flask"></i> Mailtrap</span>
                    <span onclick="fillGmail()" style="cursor:pointer;padding:0.15rem 0.5rem;border-radius:4px;background:rgba(234,67,53,0.1);color:#ff5555;font-size:0.7rem;border:1px solid rgba(234,67,53,0.2);"><i class="fa-brands fa-google"></i> Gmail SMTP</span>
                    <span onclick="fillSendGrid()" style="cursor:pointer;padding:0.15rem 0.5rem;border-radius:4px;background:rgba(0,200,100,0.1);color:#50fa7b;font-size:0.7rem;border:1px solid rgba(0,200,100,0.2);"><i class="fa-solid fa-envelope"></i> SendGrid</span>
                    <span onclick="fillMailgun()" style="cursor:pointer;padding:0.15rem 0.5rem;border-radius:4px;background:rgba(255,107,53,0.1);color:#ffb86c;font-size:0.7rem;border:1px solid rgba(255,107,53,0.2);"><i class="fa-solid fa-gun"></i> Mailgun</span>
                    <span onclick="fillPostmark()" style="cursor:pointer;padding:0.15rem 0.5rem;border-radius:4px;background:rgba(255,184,108,0.1);color:#ffb86c;font-size:0.7rem;border:1px solid rgba(255,184,108,0.2);"><i class="fa-solid fa-check"></i> Postmark</span>
                </div>
            </div>

            <!-- Botones -->
            <div style="display:flex;gap:0.5rem;justify-content:space-between;flex-wrap:wrap;">
                <div style="display:flex;gap:0.5rem;">
                    <button type="reset" class="auth-btn" style="background:transparent;border:1px solid #484f58;color:#8b949e;font-size:0.8rem;padding:0.5rem 1rem;" onclick="document.getElementById('mailForm').reset()">
                        <i class="fa-solid fa-rotate-left"></i> Restablecer
                    </button>
                </div>
                <div style="display:flex;gap:0.5rem;">
                    <a href="{{ route('admin.mail.test') }}" class="auth-btn" style="background:rgba(139,233,253,0.1);border:1px solid #8be9fd;color:#8be9fd;text-decoration:none;font-size:0.8rem;padding:0.5rem 1rem;">
                        <i class="fa-regular fa-paper-plane"></i> Probar envío
                    </a>
                    <button type="submit" class="auth-btn" style="font-size:0.8rem;padding:0.5rem 1.5rem;">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar cambios
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>

<script>
function togglePassword() {
    const input = document.getElementById('smtp_password');
    const icon = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fa-regular fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fa-regular fa-eye';
    }
}

function setPort(port) {
    document.querySelector('input[name="port"]').value = port;
}

function fillMailtrap() {
    document.querySelector('input[name="host"]').value = 'sandbox.smtp.mailtrap.io';
    document.querySelector('input[name="port"]').value = '2525';
    document.querySelector('select[name="encryption"]').value = 'tls';
}

function fillGmail() {
    document.querySelector('input[name="host"]').value = 'smtp.gmail.com';
    document.querySelector('input[name="port"]').value = '587';
    document.querySelector('select[name="encryption"]').value = 'tls';
}

function fillSendGrid() {
    document.querySelector('input[name="host"]').value = 'smtp.sendgrid.net';
    document.querySelector('input[name="port"]').value = '587';
    document.querySelector('select[name="encryption"]').value = 'tls';
}

function fillMailgun() {
    document.querySelector('input[name="host"]').value = 'smtp.mailgun.org';
    document.querySelector('input[name="port"]').value = '587';
    document.querySelector('select[name="encryption"]').value = 'tls';
}

function fillPostmark() {
    document.querySelector('input[name="host"]').value = 'smtp.postmarkapp.com';
    document.querySelector('input[name="port"]').value = '587';
    document.querySelector('select[name="encryption"]').value = 'tls';
}
</script>
