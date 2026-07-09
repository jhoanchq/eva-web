<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <label for="name" class="auth-label"><i class="fa-solid fa-user" style="color:#8be9fd;width:1.2em;"></i> Nombre completo</label>
            <input id="name" class="auth-input" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Ej: Juan Pérez">
            <x-input-error :messages="$errors->get('name')" class="auth-error" />
        </div>

        <div class="mt-4">
            <label for="email" class="auth-label"><i class="fa-regular fa-envelope" style="color:#8be9fd;width:1.2em;"></i> Correo electrónico</label>
            <input id="email" class="auth-input" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="ej: juan@test.com">
            <x-input-error :messages="$errors->get('email')" class="auth-error" />
        </div>

        <div class="mt-4">
            <label for="password" class="auth-label"><i class="fa-solid fa-lock" style="color:#8be9fd;width:1.2em;"></i> Contraseña (mín. 6 caracteres)</label>
            <input id="password" class="auth-input" type="password" name="password" required autocomplete="new-password" placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="auth-error" />
        </div>

        <div class="mt-4">
            <label for="password_confirmation" class="auth-label"><i class="fa-solid fa-check-circle" style="color:#8be9fd;width:1.2em;"></i> Confirmar contraseña</label>
            <input id="password_confirmation" class="auth-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
            <x-input-error :messages="$errors->get('password_confirmation')" class="auth-error" />
        </div>

        <div class="mt-6">
            <button type="submit" class="auth-btn w-full">
                <i class="fa-solid fa-user-plus"></i> Crear cuenta
            </button>
        </div>

        <div class="text-center mt-4">
            <span style="color:#8b949e;font-size:0.8rem;">¿Ya tienes cuenta?</span>
            <a href="{{ route('login') }}" class="auth-link ms-1">Iniciar sesión</a>
        </div>
    </form>
</x-guest-layout>
