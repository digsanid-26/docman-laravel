<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'DMS Docman') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', system-ui, sans-serif; }</style>
</head>
<body class="bg-slate-50 antialiased">

<div class="min-h-screen flex">

    {{-- LEFT: Branding Panel --}}
    <div class="hidden lg:flex lg:w-5/12 xl:w-2/5 bg-slate-900 flex-col justify-between px-12 py-10">
        <div>
            <a href="{{ route('home') }}" class="flex items-center gap-x-3 mb-16">
                <div class="w-9 h-9 bg-white rounded-2xl flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-file-lines text-slate-900 text-lg"></i>
                </div>
                <div class="leading-none">
                    <span class="font-semibold text-xl tracking-tight text-white">DMS</span>
                    <span class="font-semibold text-xl tracking-tight text-teal-400">Docman</span>
                </div>
            </a>

            <h2 class="text-4xl font-semibold text-white tracking-tight leading-tight">
                Kelola dokumen Anda<br>
                <span class="bg-gradient-to-r from-teal-400 to-emerald-400 bg-clip-text text-transparent">lebih teratur.</span>
            </h2>
            <p class="mt-4 text-slate-400 text-base leading-relaxed max-w-xs">
                Platform manajemen dokumen digital dengan workflow approval, notifikasi email, dan laporan Excel.
            </p>

            <div class="mt-10 space-y-4">
                @foreach([
                    ['icon' => 'fa-paper-plane',   'text' => 'Submit & track dokumen kapan saja'],
                    ['icon' => 'fa-check-circle',   'text' => 'Approval workflow dengan catatan admin'],
                    ['icon' => 'fa-envelope',        'text' => 'Notifikasi email otomatis real-time'],
                    ['icon' => 'fa-file-excel',      'text' => 'Export laporan dokumen ke Excel'],
                ] as $f)
                <div class="flex items-center gap-x-3 text-sm text-slate-300">
                    <div class="w-7 h-7 bg-teal-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid {{ $f['icon'] }} text-teal-400 text-xs"></i>
                    </div>
                    <span>{{ $f['text'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="text-xs text-slate-600">
            &copy; {{ date('Y') }} DMS Docman. All rights reserved.
        </div>
    </div>

    {{-- RIGHT: Form Panel --}}
    <div class="flex-1 flex flex-col justify-center items-center px-6 py-12 bg-white">

        {{-- Mobile logo --}}
        <div class="lg:hidden mb-8">
            <a href="{{ route('home') }}" class="flex items-center gap-x-2">
                <div class="w-8 h-8 bg-slate-900 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-file-lines text-white text-sm"></i>
                </div>
                <span class="font-semibold text-lg tracking-tight text-slate-900">DMS<span class="text-teal-600">Docman</span></span>
            </a>
        </div>

        <div class="w-full max-w-sm">
            {{ $slot }}
        </div>

        <p class="mt-8 text-xs text-slate-400 text-center">
            <a href="{{ route('home') }}" class="hover:text-slate-600 transition flex items-center justify-center gap-x-1">
                <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali ke halaman utama
            </a>
        </p>
    </div>

</div>

</body>
</html>
