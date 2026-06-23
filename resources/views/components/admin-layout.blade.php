@props(['title' => 'Admin Panel'])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} • DMS Docman</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .admin-sidebar { background: #0F172A; }
        .nav-active { background-color: #1E2937; border-radius: 12px; }
        .table-row { transition: background-color 0.1s ease; }
        .table-row:hover { background-color: #F8FAFC; }
        .filter-tab.active { background-color: #0F172A; color: white; box-shadow: 0 1px 3px rgba(15,23,42,0.1); }
        .action-btn { transition: all 0.2s ease; }
        .action-btn:hover { transform: translateY(-1px); }
    </style>
</head>
<body class="bg-slate-50 antialiased">

<div class="flex min-h-screen">

    {{-- SIDEBAR --}}
    <div id="sidebar" class="w-64 admin-sidebar text-white hidden lg:flex flex-col flex-shrink-0">
        <div class="px-6 py-6 border-b border-white/10">
            <div class="flex items-center gap-x-3">
                <div class="w-9 h-9 bg-white rounded-2xl flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-file-lines text-slate-900 text-lg"></i>
                </div>
                <div class="leading-none">
                    <span class="font-semibold text-lg tracking-tight text-white">DMS</span>
                    <span class="font-semibold text-lg tracking-tight text-red-400">Docman</span>
                </div>
            </div>
            <div class="mt-1 text-[11px] text-white/40 pl-0.5">Admin Panel</div>

        </div>

        <nav class="px-3 py-6 flex-1 space-y-1 text-sm">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-x-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('admin.dashboard') ? 'nav-active text-white' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-chart-line w-4 text-center"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.documents.index') }}"
               class="flex items-center gap-x-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('admin.documents.index') || request()->routeIs('admin.documents.show') ? 'nav-active text-white' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-folder-open w-4 text-center"></i>
                <span>Documents</span>
            </a>
            <a href="{{ route('admin.approved.index') }}"
               class="flex items-center gap-x-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('admin.approved.index') ? 'nav-active text-white' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-circle-check w-4 text-center"></i>
                <span>Approved Docs</span>
            </a>
            <a href="{{ route('admin.documents.export') }}"
               class="flex items-center gap-x-3 px-4 py-3 rounded-2xl text-white/70 hover:text-white hover:bg-white/5 transition">
                <i class="fa-solid fa-file-excel w-4 text-center"></i>
                <span>Export Excel</span>
            </a>
            <a href="{{ route('admin.config.index') }}"
               class="flex items-center gap-x-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('admin.config.*') ? 'nav-active text-white' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-gear w-4 text-center"></i>
                <span>Configuration</span>
            </a>
        </nav>

        <div class="px-5 py-5 border-t border-white/10 mt-auto">
            <div class="flex items-center gap-x-3">
                <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium truncate">{{ auth()->user()->name }}</div>
                    <div class="text-[11px] text-white/40">Administrator</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-white/40 hover:text-white transition" title="Logout">
                        <i class="fa-solid fa-right-from-bracket text-sm"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- TOP NAVBAR --}}
        <div class="bg-white border-b border-slate-100 px-5 py-3.5 flex items-center justify-between sticky top-0 z-40">
            <div class="flex items-center gap-x-4">
                <button onclick="document.getElementById('sidebar').classList.toggle('hidden')"
                        class="lg:hidden w-9 h-9 flex items-center justify-center text-slate-500 hover:text-slate-900 rounded-xl hover:bg-slate-100 transition">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div>
                    <h1 class="font-semibold text-lg tracking-tight text-slate-900 leading-none">{{ $title }}</h1>
                    <p class="text-[11px] text-slate-400 mt-0.5">DMS Docman Admin</p>
                </div>
            </div>

            <div class="flex items-center gap-x-3">
                <a href="{{ route('home') }}" class="hidden sm:flex items-center gap-x-1.5 text-xs text-slate-500 hover:text-slate-700 transition">
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    View Site
                </a>

                {{-- Notification Bell --}}
                <a href="{{ route('notifications.index') }}" class="relative w-9 h-9 flex items-center justify-center text-slate-500 hover:text-slate-900 rounded-xl hover:bg-slate-100 transition">
                    <i class="fa-solid fa-bell text-sm"></i>
                    @php
                        try {
                            $unreadCount = auth()->user()->unreadNotifications()->count();
                        } catch (\Exception $e) {
                            $unreadCount = 0;
                        }
                    @endphp
                    @if($unreadCount > 0)
                        <span class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center leading-none">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                    @endif
                </a>
                <div class="flex items-center gap-x-2 pl-3 border-l border-slate-100">
                    <div class="text-right hidden sm:block">
                        <div class="text-sm font-medium text-slate-800 leading-none">{{ auth()->user()->name }}</div>
                        <div class="text-[10px] text-slate-400 mt-0.5">Admin</div>
                    </div>
                    <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- PAGE CONTENT --}}
        <div class="p-6 flex-1 overflow-auto">
            {{ $slot }}
        </div>

    </div>
</div>

</body>
</html>
