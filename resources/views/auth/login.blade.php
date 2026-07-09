<x-guest-layout>
    <x-auth-session-status class="mb-4 text-green-500 text-sm" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <label for="email" class="auth-label"><i class="fa-regular fa-envelope" style="color:#8be9fd;width:1.2em;"></i> Correo electrónico</label>
            <input id="email" class="auth-input" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="ej: juan@test.com">
            <x-input-error :messages="$errors->get('email')" class="auth-error" />
        </div>

        <div class="mt-4">
            <label for="password" class="auth-label"><i class="fa-solid fa-lock" style="color:#8be9fd;width:1.2em;"></i> Contraseña</label>
            <input id="password" class="auth-input" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="auth-error" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="flex items-center gap-2">
                <input id="remember_me" type="checkbox" class="auth-checkbox rounded border-gray-300" name="remember">
                <span class="text-sm" style="color:#8b949e;">Recordarme</span>
            </label>
            @if (Route::has('password.request'))
                <a class="auth-link" href="{{ route('password.request') }}">
                    <i class="fa-regular fa-circle-question"></i> ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <div class="mt-6">
            <button type="submit" class="auth-btn w-full">
                <i class="fa-solid fa-right-to-bracket"></i> Iniciar sesión
            </button>
        </div>

        <div class="text-center mt-4">
            <span style="color:#8b949e;font-size:0.8rem;">¿No tienes cuenta?</span>
            <a href="{{ route('register') }}" class="auth-link ms-1">Registrarse</a>
        </div>
    </form>
</x-guest-layout>
