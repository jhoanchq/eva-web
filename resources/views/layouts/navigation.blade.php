<nav x-data="{ open: false }" class="nav-bar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-6">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 no-underline">
                    <i class="fa-solid fa-cloud-arrow-up" style="color:#8be9fd;font-size:1.3rem;"></i>
                    <span style="color:#e6edf3;font-weight:700;font-size:1rem;">EVA-WEB</span>
                </a>
                <div class="hidden sm:flex items-center gap-6 ms-6">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'nav-link-active' : '' }}">
                        <i class="fa-solid fa-gauge-high"></i> Dashboard
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 px-3 py-2 text-sm rounded-md nav-link hover:bg-white/5 transition">
                            <i class="fa-regular fa-circle-user" style="font-size:1.1rem;"></i>
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <div style="background:#0f1524;border:1px solid rgba(255,255,255,0.06);border-radius:12px;padding:0.3rem;">
                            <x-dropdown-link :href="route('profile.edit')" style="color:#8b949e;padding:0.4rem 0.8rem;display:block;border-radius:6px;">
                                <i class="fa-regular fa-user"></i> Perfil
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();this.closest('form').submit();" style="color:#ff5555;padding:0.4rem 0.8rem;display:block;border-radius:6px;">
                                    <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md nav-link hover:bg-white/5">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t" style="border-color:rgba(255,255,255,0.06);">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="{{ route('dashboard') }}" class="nav-link block py-2 {{ request()->routeIs('dashboard') ? 'nav-link-active' : '' }}">
                <i class="fa-solid fa-gauge-high"></i> Dashboard
            </a>
        </div>
        <div class="pt-3 pb-2 border-t" style="border-color:rgba(255,255,255,0.06);">
            <div class="px-4 pb-2">
                <div style="color:#e6edf3;font-weight:600;">{{ Auth::user()->name }}</div>
                <div style="color:#6272a4;font-size:0.8rem;">{{ Auth::user()->email }}</div>
            </div>
            <div class="space-y-1 px-4">
                <a href="{{ route('profile.edit') }}" class="nav-link block py-2"><i class="fa-regular fa-user"></i> Perfil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link block py-2" style="color:#ff5555;"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</button>
                </form>
            </div>
        </div>
    </div>
</nav>
