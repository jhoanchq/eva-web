<x-app-layout>
    <x-slot name="header"><i class="fa-regular fa-paper-plane"></i> Probar envío de correo</x-slot>

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

        @if(!$settings)
            <div class="p-3 mb-4 rounded-lg" style="background:rgba(255,184,108,0.12);border:1px solid #ffb86c;color:#ffb86c;font-size:0.85rem;">
                <i class="fa-solid fa-triangle-exclamation"></i> No hay configuración SMTP activa.
                <a href="{{ route('admin.mail.settings') }}" style="color:#8be9fd;text-decoration:underline;">Configurar ahora</a>
            </div>
        @else
            <div class="p-3 mb-4 rounded-lg" style="background:rgba(139,233,253,0.08);border:1px solid rgba(139,233,253,0.2);color:#8b949e;font-size:0.8rem;">
                <i class="fa-solid fa-circle-info" style="color:#8be9fd;"></i>
                Enviando desde <strong style="color:#e6edf3;">{{ $settings->from_address }}</strong>
                vía <strong style="color:#e6edf3;">{{ $settings->host }}:{{ $settings->port }}</strong>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.mail.test.send') }}">
            @csrf

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div style="grid-column:1/-1;">
                    <label class="auth-label"><i class="fa-regular fa-envelope" style="color:#8be9fd;width:1.2em;"></i> Destinatarios</label>
                    <input type="text" name="destinatarios" class="auth-input" value="{{ old('destinatarios') }}" placeholder="user1@test.com, user2@test.com; otro@test.com" required>
                    <p style="color:#484f58;font-size:0.7rem;margin-top:0.2rem;">Separa múltiples destinatarios con coma (,), punto y coma (;) o salto de línea.</p>
                    @error('destinatarios')<div class="auth-error">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="auth-label"><i class="fa-solid fa-tag" style="color:#8be9fd;width:1.2em;"></i> Etiqueta (opcional)</label>
                    <input type="text" name="etiqueta" class="auth-input" value="{{ old('etiqueta') }}" placeholder="Ej: TEST, Demo, Clase">
                    <p style="color:#484f58;font-size:0.7rem;margin-top:0.2rem;">Aparecerá como [ETIQUETA] en el asunto.</p>
                </div>
                <div>
                    <label class="auth-label"><i class="fa-regular fa-pen-to-square" style="color:#8be9fd;width:1.2em;"></i> Asunto</label>
                    <input type="text" name="asunto" class="auth-input" value="{{ old('asunto', 'Correo de prueba EVA-WEB') }}" placeholder="Asunto del correo" required>
                    @error('asunto')<div class="auth-error">{{ $message }}</div>@enderror
                </div>
                <div style="grid-column:1/-1;">
                    <label class="auth-label"><i class="fa-regular fa-message" style="color:#8be9fd;width:1.2em;"></i> Mensaje</label>
                    <textarea name="mensaje" class="auth-input" rows="5" placeholder="Escribe tu mensaje aquí..." required style="resize:vertical;">{{ old('mensaje', 'Este es un correo de prueba enviado desde EVA-WEB.') }}</textarea>
                    @error('mensaje')<div class="auth-error">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="auth-label"><i class="fa-regular fa-copy" style="color:#8be9fd;width:1.2em;"></i> CC (opcional)</label>
                    <input type="text" name="cc" class="auth-input" value="{{ old('cc') }}" placeholder="cc1@test.com, cc2@test.com">
                </div>
                <div>
                    <label class="auth-label"><i class="fa-regular fa-eye-slash" style="color:#8be9fd;width:1.2em;"></i> BCC (opcional)</label>
                    <input type="text" name="bcc" class="auth-input" value="{{ old('bcc') }}" placeholder="bcc1@test.com">
                </div>
            </div>

            <div class="mt-6" style="display:flex;gap:0.5rem;justify-content:flex-end;">
                <a href="{{ route('admin.mail.settings') }}" class="auth-btn" style="background:transparent;border:1px solid #8b949e;color:#8b949e;text-decoration:none;">
                    <i class="fa-solid fa-gear"></i> Configuración
                </a>
                <button type="submit" class="auth-btn">
                    <i class="fa-regular fa-paper-plane"></i> Enviar correo
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
