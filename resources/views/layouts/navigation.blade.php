<nav x-data="{ open: false }" class="bg-white border-b border-slate-100">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('documents.index') }}" class="flex items-center gap-x-2">
                        <div class="w-8 h-8 bg-slate-900 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-file-lines text-white text-sm"></i>
                        </div>
                        <span class="font-semibold text-base tracking-tight text-slate-900 hidden sm:inline">
                            DMS<span class="text-teal-600">Docman</span>
                        </span>
                    </a>
                </div>

                <!-- Nav Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-8 sm:flex text-sm">
                    <a href="{{ route('documents.index') }}"
                       class="inline-flex items-center gap-x-1.5 pt-1 border-b-2 font-medium transition {{ request()->routeIs('documents.*') ? 'border-teal-500 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                        <i class="fa-solid fa-folder-open text-xs"></i> Dokumen Saya
                    </a>
                </div>
            </div>

            <!-- Right: Bell + User Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:gap-x-2">

                {{-- Notification Bell --}}
                @php $unreadCount = Auth::user()->unreadNotifications()->count(); @endphp
                <a href="{{ route('notifications.index') }}"
                   class="relative w-9 h-9 flex items-center justify-center text-slate-500 hover:text-slate-900 rounded-xl hover:bg-slate-100 transition">
                    <i class="fa-solid fa-bell text-sm"></i>
                    @if($unreadCount > 0)
                        <span class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center leading-none">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                    @endif
                </a>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-x-2 px-3 py-2 text-sm font-medium text-slate-700 hover:text-slate-900 rounded-xl hover:bg-slate-100 transition">
                            <div class="w-7 h-7 bg-teal-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="hidden md:inline">{{ Auth::user()->name }}</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            <i class="fa-solid fa-user text-xs mr-2 text-slate-400"></i> Profil
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('notifications.index')">
                            <i class="fa-solid fa-bell text-xs mr-2 text-slate-400"></i> Notifikasi
                            @if($unreadCount > 0)
                                <span class="ml-1 px-1.5 py-0.5 bg-red-100 text-red-600 text-[10px] rounded-full font-semibold">{{ $unreadCount }}</span>
                            @endif
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class="fa-solid fa-right-from-bracket text-xs mr-2 text-slate-400"></i> Keluar
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-slate-100">
        <div class="pt-2 pb-3 space-y-1 px-3">
            <x-responsive-nav-link :href="route('documents.index')" :active="request()->routeIs('documents.*')">
                <i class="fa-solid fa-folder-open text-xs mr-2"></i> Dokumen Saya
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('notifications.index')" :active="request()->routeIs('notifications.*')">
                <i class="fa-solid fa-bell text-xs mr-2"></i> Notifikasi
                @if($unreadCount > 0)
                    <span class="ml-1 px-1.5 py-0.5 bg-red-100 text-red-600 text-[10px] rounded-full font-semibold">{{ $unreadCount }}</span>
                @endif
            </x-responsive-nav-link>
        </div>
        <div class="pt-4 pb-1 border-t border-slate-100">
            <div class="px-4">
                <div class="font-medium text-sm text-slate-800">{{ Auth::user()->name }}</div>
                <div class="text-xs text-slate-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1 px-3">
                <x-responsive-nav-link :href="route('profile.edit')">Profil</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        Keluar
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
