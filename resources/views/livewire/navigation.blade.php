<nav class="fixed top-0 right-0 z-30 bg-slate-800 shadow-md lg:left-20">
    <div class="px-4 py-3 flex justify-end items-center">
        <div class="flex items-center space-x-4">
            <div x-data="{ userMenuOpen: false }" class="relative">
                <button @click="userMenuOpen = !userMenuOpen"
                    class="flex items-center space-x-2 text-slate-300 hover:text-white">
                    <i class="bi bi-person-circle text-xl"></i>
                    <span class="text-sm font-medium hidden sm:inline">{{ auth()->user()->name ?? 'Usuario' }}</span>
                    <i class="bi bi-chevron-down text-sm transition-transform"
                        :class="{ 'rotate-180': userMenuOpen }"></i>
                </button>

                <div x-show="userMenuOpen" @click.away="userMenuOpen = false" x-cloak
                    class="absolute right-0 mt-2 w-48 bg-slate-800 rounded-md shadow-lg py-1 z-50 border border-slate-700">
                    <a href="{{ route('profile') }}"
                        class="block px-4 py-2 text-sm text-slate-300 hover:bg-slate-700 hover:text-white">
                        <i class="bi bi-person mr-2"></i> Mi Perfil
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm text-slate-300 hover:bg-slate-700 hover:text-white">
                            <i class="bi bi-box-arrow-right mr-2"></i> Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
