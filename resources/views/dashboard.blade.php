<x-app-layout>
    <x-slot name="header">
        <i class="fa-solid fa-gauge-high"></i> Dashboard
    </x-slot>

    <div class="main-card p-6 sm:p-8">
        <div class="flex items-center gap-4 mb-6">
            <div style="width:60px;height:60px;border-radius:50%;background:rgba(139,233,253,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fa-solid fa-user" style="color:#8be9fd;font-size:1.5rem;"></i>
            </div>
            <div>
                <h1 style="font-size:1.2rem;font-weight:700;color:#e6edf3;">¡Bienvenido, {{ Auth::user()->name }}!</h1>
                <p style="color:#6272a4;font-size:0.85rem;">{{ Auth::user()->email }}</p>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:1rem;margin-top:1rem;">
            <div style="background:rgba(139,233,253,0.06);border:1px solid rgba(139,233,253,0.15);border-radius:12px;padding:1.2rem;">
                <i class="fa-regular fa-file-lines" style="color:#8be9fd;font-size:1.5rem;"></i>
                <h3 style="color:#e6edf3;font-size:0.9rem;margin:0.5rem 0 0.2rem;">API Avatar</h3>
                <p style="color:#6272a4;font-size:0.75rem;">POST /api/avatar · GET /api/avatar/{id} · DELETE /api/avatar</p>
            </div>
            <div style="background:rgba(80,250,123,0.06);border:1px solid rgba(80,250,123,0.15);border-radius:12px;padding:1.2rem;">
                <i class="fa-solid fa-lock" style="color:#50fa7b;font-size:1.5rem;"></i>
                <h3 style="color:#e6edf3;font-size:0.9rem;margin:0.5rem 0 0.2rem;">Autenticación</h3>
                <p style="color:#6272a4;font-size:0.75rem;">POST /api/register · POST /api/login · POST /api/logout</p>
            </div>
            <div style="background:rgba(255,184,108,0.06);border:1px solid rgba(255,184,108,0.15);border-radius:12px;padding:1.2rem;">
                <i class="fa-regular fa-envelope" style="color:#ffb86c;font-size:1.5rem;"></i>
                <h3 style="color:#e6edf3;font-size:0.9rem;margin:0.5rem 0 0.2rem;">Correo</h3>
                <p style="color:#6272a4;font-size:0.75rem;">Bienvenida al registrarse vía Mailtrap</p>
            </div>
            <div style="background:rgba(255,121,198,0.06);border:1px solid rgba(255,121,198,0.15);border-radius:12px;padding:1.2rem;">
                <i class="fa-brands fa-laravel" style="color:#ff79c6;font-size:1.5rem;"></i>
                <h3 style="color:#e6edf3;font-size:0.9rem;margin:0.5rem 0 0.2rem;">Laravel 11</h3>
                <p style="color:#6272a4;font-size:0.75rem;">Sanctum · Breeze · MySQL · Tailwind</p>
            </div>
        </div>
    </div>
</x-app-layout>
