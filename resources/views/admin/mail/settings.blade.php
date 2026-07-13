<x-app-layout>
    <x-slot name="header"><i class="fa-solid fa-gear"></i> Configuración de correo</x-slot>

    <div class="main-card p-6 sm:p-8">
        @if(session('success'))
            <div class="p-3 mb-4 rounded-lg" style="background:rgba(0,200,100,0.12);border:1px solid #00c864;color:#00c864;font-size:0.85rem;">
                <i class="fa-regular fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-3 mb-4 rounded-lg" style="background:rgba(255,50,50,0.12);border:1px solid #ff5555;color:#ff5555;font-size:0.85rem;">
                <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.mail.settings.update') }}">
            @csrf

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div>
                    <label class="auth-label">Mailer</label>
                    <select name="mailer" class="auth-input">
                        <option value="smtp" {{ $settings->mailer == 'smtp' ? 'selected' : '' }}>SMTP</option>
                        <option value="sendmail" {{ $settings->mailer == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                        <option value="log" {{ $settings->mailer == 'log' ? 'selected' : '' }}>Log (solo pruebas)</option>
                    </select>
                    @error('mailer')<div class="auth-error">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="auth-label">Host SMTP</label>
                    <input type="text" name="host" class="auth-input" value="{{ old('host', $settings->host) }}" placeholder="sandbox.smtp.mailtrap.io">
                    @error('host')<div class="auth-error">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="auth-label">Puerto</label>
                    <input type="number" name="port" class="auth-input" value="{{ old('port', $settings->port) }}" placeholder="2525">
                    @error('port')<div class="auth-error">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="auth-label">Encriptación</label>
                    <select name="encryption" class="auth-input">
                        <option value="tls" {{ $settings->encryption == 'tls' ? 'selected' : '' }}>TLS</option>
                        <option value="ssl" {{ $settings->encryption == 'ssl' ? 'selected' : '' }}>SSL</option>
                        <option value="null" {{ $settings->encryption == 'null' ? 'selected' : '' }}>Ninguna</option>
                    </select>
                </div>
                <div>
                    <label class="auth-label">Usuario SMTP</label>
                    <input type="text" name="username" class="auth-input" value="{{ old('username', $settings->username) }}" placeholder="Usuario">
                </div>
                <div>
                    <label class="auth-label">Contraseña SMTP</label>
                    <input type="password" name="password" class="auth-input" placeholder="•••••••• (dejar vacío para mantener)">
                    <p style="color:#484f58;font-size:0.7rem;margin-top:0.2rem;">Si está configurada, dejar vacío para mantener la actual.</p>
                </div>
                <div>
                    <label class="auth-label">Correo remitente (From)</label>
                    <input type="email" name="from_address" class="auth-input" value="{{ old('from_address', $settings->from_address) }}" placeholder="noreply@eva-web.test">
                </div>
                <div>
                    <label class="auth-label">Nombre remitente</label>
                    <input type="text" name="from_name" class="auth-input" value="{{ old('from_name', $settings->from_name) }}" placeholder="EVA-WEB">
                </div>
            </div>

            <div class="mt-6" style="display:flex;gap:0.5rem;justify-content:flex-end;">
                <a href="{{ route('admin.mail.test') }}" class="auth-btn" style="background:transparent;border:1px solid #8be9fd;color:#8be9fd;text-decoration:none;">
                    <i class="fa-regular fa-flask"></i> Probar envío
                </a>
                <button type="submit" class="auth-btn">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar configuración
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
