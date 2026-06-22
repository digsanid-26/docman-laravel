<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DMS Docman • Document Management System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .feature-card { transition: all 0.3s cubic-bezier(0.4,0,0.2,1); }
        .feature-card:hover { transform: translateY(-4px); box-shadow: 0 20px 25px -5px rgb(15 23 42/0.06); }
        .step-number { width:42px;height:42px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.05rem;background:linear-gradient(135deg,#0D9488,#14B8A6);color:white;border-radius:9999px;box-shadow:0 10px 15px -3px rgb(13 148 136/0.3); }
        .elegant-shadow { box-shadow: 0 10px 15px -3px rgb(15 23 42/0.07), 0 4px 6px -4px rgb(15 23 42/0.07); }
        .nav-scrolled { box-shadow: 0 1px 0 0 rgb(15 23 42/0.05); background-color:rgba(255,255,255,0.97); backdrop-filter:blur(12px); }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

    <!-- Navbar -->
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-white border-b border-slate-100">
        <div class="max-w-screen-xl mx-auto px-6 md:px-10">
            <div class="py-4 flex items-center justify-between">
                <div class="flex items-center gap-x-3">
                    <div class="w-9 h-9 bg-slate-900 rounded-2xl flex items-center justify-center shadow-sm">
                        <i class="fa-solid fa-file-lines text-white text-lg"></i>
                    </div>
                    <div class="leading-none">
                        <span class="font-semibold text-xl tracking-tight text-slate-900">DMS</span>
                        <span class="font-semibold text-xl tracking-tight text-teal-600">Docman</span>
                    </div>
                </div>

                <div class="hidden md:flex items-center gap-x-8 text-sm font-medium">
                    <a href="#fitur" class="text-slate-600 hover:text-slate-900 transition-colors">Fitur</a>
                    <a href="#cara-kerja" class="text-slate-600 hover:text-slate-900 transition-colors">Cara Kerja</a>
                    <a href="#keunggulan" class="text-slate-600 hover:text-slate-900 transition-colors">Keunggulan</a>
                </div>

                <div class="flex items-center gap-x-2">
                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('documents.index') }}"
                           class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-full transition">
                            <i class="fa-solid fa-table-columns mr-1.5"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="px-5 py-2 text-sm font-semibold text-slate-700 hover:text-slate-900 hover:bg-slate-100 transition rounded-full border border-slate-200 hover:border-slate-300">
                            <i class="fa-solid fa-right-to-bracket mr-1.5"></i> Masuk
                        </a>
                        <a href="{{ route('register') }}"
                           class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-full transition">
                            Daftar Gratis
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="pt-28 pb-16 md:pt-32 md:pb-20 max-w-screen-xl mx-auto px-6 md:px-10">
        <div class="grid md:grid-cols-12 gap-10 items-center">
            <!-- Left -->
            <div class="md:col-span-7 max-w-2xl">
                <div class="inline-flex items-center gap-x-2 bg-white border border-slate-200 text-slate-600 px-4 h-8 rounded-full text-xs font-medium tracking-wide mb-6">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                    <span>Sistem pengelolaan dokumen digital yang aman</span>
                </div>

                <h1 class="text-5xl md:text-6xl leading-[1.05] tracking-tight font-semibold text-slate-900">
                    Dokumen Anda.<br>
                    <span class="bg-gradient-to-r from-teal-600 to-emerald-500 bg-clip-text text-transparent">Dikelola dengan teratur.</span>
                </h1>

                <p class="mt-6 max-w-lg text-lg text-slate-600 leading-relaxed">
                    Platform manajemen dokumen yang memudahkan pengiriman, review, dan persetujuan dokumen — 
                    dilengkapi notifikasi email otomatis dan laporan Excel.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 mt-10">
                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('documents.index') }}"
                           class="px-8 h-14 bg-slate-900 hover:bg-black transition text-white font-semibold text-base rounded-full flex items-center justify-center gap-x-2 shadow-lg shadow-slate-900/20 group">
                            <span>Buka Dashboard</span>
                            <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                           class="px-8 h-14 bg-slate-900 hover:bg-black transition text-white font-semibold text-base rounded-full flex items-center justify-center gap-x-2 shadow-lg shadow-slate-900/20 group">
                            <span>Mulai Sekarang</span>
                            <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </a>
                        <a href="{{ route('login') }}"
                           class="px-8 h-14 border border-slate-300 hover:bg-white transition text-slate-700 font-semibold text-base rounded-full flex items-center justify-center gap-x-2">
                            <i class="fa-solid fa-right-to-bracket mr-1"></i>
                            <span>Sudah punya akun?</span>
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Right: Mock Dashboard -->
            <div class="md:col-span-5 mt-8 md:mt-0">
                <div class="relative mx-auto max-w-sm">
                    <div class="bg-white rounded-3xl p-2 shadow-2xl shadow-slate-900/10 border border-slate-100">
                        <div class="bg-slate-900 rounded-2xl overflow-hidden">
                            <div class="px-5 pt-5 pb-4">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <div class="text-white text-sm font-medium">Admin Panel</div>
                                        <div class="text-emerald-400 text-xs">12 dokumen menunggu review</div>
                                    </div>
                                    <div class="px-3 py-1 bg-white/10 text-white text-xs rounded-full flex items-center gap-x-1.5">
                                        <i class="fa-solid fa-circle-check text-emerald-400 text-[10px]"></i>
                                        <span class="text-[10px]">Sistem Aktif</span>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex items-center gap-x-3 bg-white/5 p-3 rounded-2xl">
                                        <div class="w-8 h-8 bg-teal-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <i class="fa-solid fa-file-pdf text-teal-400 text-sm"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-white text-sm font-medium truncate">Kontrak_Kerjasama_2026.pdf</div>
                                            <div class="text-white/50 text-[10px]">Dikirim 5 menit lalu</div>
                                        </div>
                                        <span class="text-[10px] bg-yellow-500/20 text-yellow-300 px-2 py-0.5 rounded-full">Review</span>
                                    </div>
                                    <div class="flex items-center gap-x-3 bg-white/5 p-3 rounded-2xl">
                                        <div class="w-8 h-8 bg-emerald-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <i class="fa-solid fa-file-word text-emerald-400 text-sm"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-white text-sm font-medium truncate">Laporan_Keuangan_Q2.docx</div>
                                            <div class="text-white/50 text-[10px]">Disetujui • 2 jam lalu</div>
                                        </div>
                                        <i class="fa-solid fa-check-circle text-emerald-400"></i>
                                    </div>
                                    <div class="flex items-center gap-x-3 bg-white/5 p-3 rounded-2xl">
                                        <div class="w-8 h-8 bg-red-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <i class="fa-solid fa-file-image text-red-400 text-sm"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-white text-sm font-medium truncate">SK_Pengangkatan_Jabatan.pdf</div>
                                            <div class="text-white/50 text-[10px]">Perlu revisi</div>
                                        </div>
                                        <span class="text-[10px] bg-red-500/20 text-red-300 px-2 py-0.5 rounded-full">Revisi</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -top-3 -right-3 bg-white shadow-lg border border-slate-100 px-4 h-9 rounded-full flex items-center text-xs font-medium text-slate-700">
                        <i class="fa-solid fa-envelope text-teal-500 mr-2"></i>
                        <span>Notifikasi Email Otomatis</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats bar -->
    <div class="max-w-screen-xl mx-auto px-6 md:px-10 pb-12">
        <div class="flex flex-wrap justify-center items-center gap-x-12 gap-y-5 py-8 border-y border-slate-100">
            @foreach([
                ['value' => '100%', 'label' => 'Digital & Paperless'],
                ['value' => '< 1 Min', 'label' => 'Waktu Submit Dokumen'],
                ['value' => 'Real-time', 'label' => 'Notifikasi Email'],
                ['value' => 'Excel', 'label' => 'Export Laporan'],
            ] as $stat)
            <div class="text-center">
                <div class="text-2xl font-bold text-slate-900 tracking-tight">{{ $stat['value'] }}</div>
                <div class="text-xs text-slate-500 mt-0.5">{{ $stat['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Features -->
    <section id="fitur" class="max-w-screen-xl mx-auto px-6 md:px-10 py-16 border-t border-slate-100">
        <div class="max-w-2xl mb-12">
            <div class="uppercase tracking-[2px] text-teal-600 text-xs font-semibold mb-2">Fitur Unggulan</div>
            <h2 class="text-4xl font-semibold tracking-tight text-slate-900 leading-tight">Semua yang Anda butuhkan.<br>Tanpa kerumitan.</h2>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach([
                ['icon' => 'fa-paper-plane', 'color' => 'bg-teal-100 text-teal-600', 'title' => 'Submit Dokumen Mudah', 'desc' => 'Isi form, upload file, dan kirim dokumen dalam hitungan detik. Mendukung PDF, Word, JPG, dan PNG hingga 10 MB.'],
                ['icon' => 'fa-magnifying-glass', 'color' => 'bg-teal-100 text-teal-600', 'title' => 'Review & Approval Workflow', 'desc' => 'Admin mereview setiap dokumen, memberikan catatan revisi atau langsung menyetujui dengan satu klik.'],
                ['icon' => 'fa-envelope', 'color' => 'bg-teal-100 text-teal-600', 'title' => 'Notifikasi Email Otomatis', 'desc' => 'Email terkirim otomatis ke user saat dokumen perlu direvisi, disetujui, atau ditolak. Tidak ada yang terlewat.'],
                ['icon' => 'fa-folder-open', 'color' => 'bg-teal-100 text-teal-600', 'title' => 'Arsip Dokumen Terstruktur', 'desc' => 'Dokumen yang disetujui otomatis tersimpan di direktori arsip dengan penamaan berurutan dan terstruktur.'],
                ['icon' => 'fa-file-excel', 'color' => 'bg-teal-100 text-teal-600', 'title' => 'Export Excel Laporan', 'desc' => 'Admin dapat mengekspor seluruh daftar dokumen beserta statusnya ke file Excel kapan saja, dengan filter status.'],
                ['icon' => 'fa-shield-halved', 'color' => 'bg-teal-100 text-teal-600', 'title' => 'Role-based Access Control', 'desc' => 'Akses dipisah antara User (submit & pantau) dan Admin (review, approve, export). Data terlindungi dengan baik.'],
            ] as $f)
            <div class="feature-card bg-white border border-slate-100 rounded-3xl p-7 elegant-shadow">
                <div class="w-12 h-12 {{ $f['color'] }} rounded-2xl flex items-center justify-center mb-5">
                    <i class="fa-solid {{ $f['icon'] }} text-xl"></i>
                </div>
                <h3 class="font-semibold text-lg tracking-tight text-slate-900">{{ $f['title'] }}</h3>
                <p class="mt-2 text-slate-600 leading-relaxed text-sm">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </section>

    <!-- How it works -->
    <section id="cara-kerja" class="max-w-screen-xl mx-auto px-6 md:px-10 py-16 bg-white border-t border-slate-100">
        <div class="max-w-2xl mb-14">
            <div class="uppercase tracking-[2px] text-teal-600 text-xs font-semibold mb-2">Proses Sederhana</div>
            <h2 class="text-4xl font-semibold tracking-tight text-slate-900">Mulai dalam 4 langkah mudah.</h2>
            <p class="mt-3 text-lg text-slate-600">Dari pendaftaran hingga dokumen tersimpan di arsip hanya dalam hitungan menit.</p>
        </div>
        <div class="grid md:grid-cols-4 gap-6">
            @foreach([
                ['num' => '01', 'title' => 'Daftar & Masuk', 'desc' => 'Buat akun baru dengan email dan password. Login menggunakan akun yang sudah terdaftar.'],
                ['num' => '02', 'title' => 'Submit Dokumen', 'desc' => 'Isi judul, jenis, tanggal, deskripsi, dan upload file dokumen. Klik Kirim.'],
                ['num' => '03', 'title' => 'Admin Review', 'desc' => 'Admin menerima notifikasi, membuka dokumen, dan memberikan catatan atau keputusan approval.'],
                ['num' => '04', 'title' => 'Tersimpan di Arsip', 'desc' => 'Dokumen yang disetujui otomatis tersimpan di direktori arsip. Email konfirmasi dikirim ke user.'],
            ] as $step)
            <div class="group">
                <div class="flex items-center gap-x-4 mb-5">
                    <div class="step-number flex-shrink-0">{{ $step['num'] }}</div>
                    @if($step['num'] !== '04')
                        <div class="h-px flex-1 bg-slate-200 group-hover:bg-teal-200 transition-colors"></div>
                    @endif
                </div>
                <h4 class="font-semibold text-lg tracking-tight text-slate-900">{{ $step['title'] }}</h4>
                <p class="mt-2 text-slate-600 text-sm leading-relaxed">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Advantages -->
    <section id="keunggulan" class="max-w-screen-xl mx-auto px-6 md:px-10 py-16 border-t border-slate-100">
        <div class="grid md:grid-cols-12 gap-x-12 items-start">
            <div class="md:col-span-5">
                <div class="sticky top-24">
                    <div class="uppercase tracking-[2px] text-teal-600 text-xs font-semibold mb-3">Keunggulan Sistem</div>
                    <h2 class="text-4xl font-semibold tracking-tight text-slate-900 leading-tight">Dokumen Anda<br>adalah prioritas kami.</h2>
                    <div class="mt-8 space-y-4 text-sm text-slate-700">
                        @foreach([
                            'File tersimpan aman di server dengan akses terkontrol',
                            'Riwayat review lengkap untuk setiap dokumen',
                            'Status dokumen dapat dipantau real-time oleh user',
                            'Export laporan Excel untuk keperluan audit & rekap',
                            'Email notifikasi di setiap perubahan status dokumen',
                        ] as $item)
                        <div class="flex items-start gap-x-3">
                            <i class="fa-solid fa-check text-emerald-500 mt-0.5 flex-shrink-0"></i>
                            <span>{{ $item }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="md:col-span-7 mt-10 md:mt-0">
                <div class="grid grid-cols-2 gap-4">
                    @foreach([
                        ['val' => '5 Status', 'label' => 'Lifecycle Dokumen', 'note' => 'Submitted → Approved'],
                        ['val' => '4 Email', 'label' => 'Notifikasi Otomatis', 'note' => 'Submit, Revisi, Approve, Tolak'],
                        ['val' => 'Excel', 'label' => 'Export Laporan', 'note' => 'Filter per status dokumen'],
                        ['val' => '2 Role', 'label' => 'Hak Akses', 'note' => 'User & Admin terpisah'],
                    ] as $m)
                    <div class="bg-white border border-slate-100 p-6 rounded-3xl elegant-shadow">
                        <div class="text-3xl font-semibold tracking-tight text-slate-900">{{ $m['val'] }}</div>
                        <div class="text-sm font-medium text-slate-500 mt-1">{{ $m['label'] }}</div>
                        <div class="text-xs text-emerald-600 mt-3">• {{ $m['note'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="max-w-screen-xl mx-auto px-6 md:px-10 py-20">
        <div class="bg-slate-900 rounded-3xl px-10 py-14 text-center text-white">
            <div class="max-w-lg mx-auto">
                <h2 class="text-4xl font-semibold tracking-tight">Siap mengelola dokumen<br>dengan lebih teratur?</h2>
                <p class="mt-3 text-white/70">Daftar sekarang, gratis. Tidak perlu kartu kredit.</p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('documents.index') }}"
                           class="px-9 h-14 bg-white text-slate-900 hover:bg-slate-100 transition font-semibold rounded-full text-base flex items-center justify-center gap-x-2">
                            <i class="fa-solid fa-table-columns mr-1"></i> Buka Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                           class="px-9 h-14 bg-white text-slate-900 hover:bg-slate-100 transition font-semibold rounded-full text-base flex items-center justify-center gap-x-2">
                            Daftar Sekarang — Gratis
                        </a>
                        <a href="{{ route('login') }}"
                           class="px-9 h-14 border border-white/30 hover:bg-white/10 transition font-medium rounded-full text-base flex items-center justify-center gap-x-2">
                            Sudah punya akun? Masuk
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-slate-100 bg-white">
        <div class="max-w-screen-xl mx-auto px-8 py-8 text-sm text-slate-500">
            <div class="flex flex-col md:flex-row justify-between items-center gap-y-3">
                <div class="flex items-center gap-x-2">
                    <div class="w-6 h-6 bg-slate-900 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-file-lines text-white text-xs"></i>
                    </div>
                    <span class="font-medium text-slate-700">DMS Docman</span>
                    <span class="text-slate-400">&copy; {{ date('Y') }}</span>
                </div>
                <div class="flex gap-x-6">
                    <a href="{{ route('login') }}" class="hover:text-slate-700 transition">Masuk</a>
                    <a href="{{ route('register') }}" class="hover:text-slate-700 transition">Daftar</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                navbar.classList.add('nav-scrolled');
            } else {
                navbar.classList.remove('nav-scrolled');
            }
        });
    </script>

</body>
</html>
