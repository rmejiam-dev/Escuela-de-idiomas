<div x-data="{ expanded: false }" class="fixed top-0 left-0 z-40 h-screen" @mouseenter="expanded = true"
    @mouseleave="expanded = false">

    <aside class="h-full flex flex-col bg-gradient-to-b from-slate-800 to-slate-900 shadow-2xl overflow-y-auto"
        :class="expanded ? 'w-64' : 'w-20'">

        <!-- Logo -->
        <div class="p-4 border-b border-slate-700 text-center sticky top-0 bg-slate-800">
            <h2 class="font-bold text-white transition-all duration-300" :class="expanded ? 'text-xl' : 'text-sm'">
                <span x-show="expanded">{{ config('app.name') }}</span>
                <span x-show="!expanded">EI</span>
            </h2>
        </div>

        <!-- Menú -->
        <nav class="flex-1 px-2 py-4 space-y-1">
            @can('view dashboard')
                <a href="{{ route('dashboard') }}"
                    class="flex items-center rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-slate-700 text-white shadow-md' : '' }}"
                    :class="expanded ? 'justify-start px-3 py-2.5' : 'justify-center px-2 py-2.5'">
                    <i class="bi bi-grid w-5 h-5"></i>
                    <span x-show="expanded" class="ml-3 whitespace-nowrap">Dashboard</span>
                </a>
            @endcan

            @canany(['view all procedures', 'view own procedures'])
                <div x-data="{ subOpen: false }">
                    <button @click="if(expanded) subOpen = !subOpen"
                        class="w-full flex items-center rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white transition-all duration-200"
                        :class="expanded ? 'justify-between px-3 py-2.5' : 'justify-center px-2 py-2.5'">
                        <div class="flex items-center">
                            <i class="bi bi-files w-5 h-5"></i>
                            <span x-show="expanded" class="ml-3 whitespace-nowrap">Trámites</span>
                        </div>
                        <i x-show="expanded" class="bi text-sm transition-transform duration-200"
                            :class="subOpen ? 'bi-chevron-down rotate-180' : 'bi-chevron-right'"></i>
                    </button>
                    <div x-show="expanded && subOpen" x-collapse class="ml-6 mt-1 space-y-1 border-l border-slate-700 pl-2">
                        @can('create procedures')
                            <a href="{{ route('procedures.create') }}"
                                class="flex items-center px-3 py-2 rounded-lg text-slate-400 hover:bg-slate-700 hover:text-white text-sm">
                                <i class="bi bi-plus-circle w-4 h-4"></i>
                                <span class="ml-2">Nuevo Trámite</span>
                            </a>
                        @endcan
                        <a href="{{ route('procedures.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-slate-400 hover:bg-slate-700 hover:text-white text-sm">
                            <i class="bi bi-list-ul w-4 h-4"></i>
                            <span class="ml-2">Trámites</span>
                        </a>
                    </div>
                </div>
            @endcanany

            @can('manage users')
                <div x-data="{ subOpen: false }">
                    <button @click="if(expanded) subOpen = !subOpen"
                        class="w-full flex items-center rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white transition-all duration-200"
                        :class="expanded ? 'justify-between px-3 py-2.5' : 'justify-center px-2 py-2.5'">
                        <div class="flex items-center">
                            <i class="bi bi-people w-5 h-5"></i>
                            <span x-show="expanded" class="ml-3 whitespace-nowrap">Usuarios</span>
                        </div>
                        <i x-show="expanded" class="bi text-sm transition-transform duration-200"
                            :class="subOpen ? 'bi-chevron-down rotate-180' : 'bi-chevron-right'"></i>
                    </button>
                    <div x-show="expanded && subOpen" x-collapse class="ml-6 mt-1 space-y-1 border-l border-slate-700 pl-2">
                        <a href="{{ route('users.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-slate-400 hover:bg-slate-700 hover:text-white text-sm">
                            <i class="bi bi-person-video2 w-4 h-4"></i>
                            <span class="ml-2">Ver Usuarios</span>
                        </a>
                        <a href="{{ route('users.create') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-slate-400 hover:bg-slate-700 hover:text-white text-sm">
                            <i class="bi bi-person-plus w-4 h-4"></i>
                            <span class="ml-2">Registrar Usuario</span>
                        </a>
                    </div>
                </div>
            @endcan

            @can('manage payments')
                <a href="{{ route('payments.index') }}"
                    class="flex items-center rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white transition-all duration-200 {{ request()->routeIs('payments.*') ? 'bg-slate-700 text-white shadow-md' : '' }}"
                    :class="expanded ? 'justify-start px-3 py-2.5' : 'justify-center px-2 py-2.5'">
                    <i class="bi bi-credit-card w-5 h-5"></i>
                    <span x-show="expanded" class="ml-3 whitespace-nowrap">Pagos</span>
                </a>
            @endcan

            @can('manage pre_enrollments')
                <a href="{{ route('pre-enrollments.index') }}"
                    class="flex items-center rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white transition-all duration-200 {{ request()->routeIs('pre-enrollments.*') ? 'bg-slate-700 text-white shadow-md' : '' }}"
                    :class="expanded ? 'justify-start px-3 py-2.5' : 'justify-center px-2 py-2.5'">
                    <i class="bi bi-journal-text w-5 h-5"></i>
                    <span x-show="expanded" class="ml-3 whitespace-nowrap">Preinscripciones</span>
                </a>
            @endcan

            @can('manage roles')
                <a href="{{ route('roles.index') }}"
                    class="flex items-center rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white transition-all duration-200 {{ request()->routeIs('roles.*') ? 'bg-slate-700 text-white shadow-md' : '' }}"
                    :class="expanded ? 'justify-start px-3 py-2.5' : 'justify-center px-2 py-2.5'">
                    <i class="bi bi-person-badge w-5 h-5"></i>
                    <span x-show="expanded" class="ml-3 whitespace-nowrap">Roles</span>
                </a>
            @endcan

            @can('view reports')
                <a href="{{ route('reports.index') }}"
                    class="flex items-center rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white transition-all duration-200 {{ request()->routeIs('reports.*') ? 'bg-slate-700 text-white shadow-md' : '' }}"
                    :class="expanded ? 'justify-start px-3 py-2.5' : 'justify-center px-2 py-2.5'">
                    <i class="bi bi-graph-up w-5 h-5"></i>
                    <span x-show="expanded" class="ml-3 whitespace-nowrap">Reportes</span>
                </a>
            @endcan
        </nav>

        <!-- Cerrar sesión -->
        <div class="p-4 border-t border-slate-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center rounded-lg text-slate-300 hover:bg-slate-700 transition-all duration-200"
                    :class="expanded ? 'justify-start px-3 py-2' : 'justify-center px-2 py-2'">
                    <i class="bi bi-box-arrow-right w-5 h-5"></i>
                    <span x-show="expanded" class="ml-3">Cerrar Sesión</span>
                </button>
            </form>
        </div>
    </aside>
</div>
