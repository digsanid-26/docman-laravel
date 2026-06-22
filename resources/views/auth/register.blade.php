<x-guest-layout>

    <div class="mb-8">
        <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Buat akun baru</h1>
        <p class="text-sm text-slate-500 mt-1">Daftar dan mulai kelola dokumen Anda</p>
    </div>

    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-2xl flex items-center gap-x-2">
            <i class="fa-solid fa-circle-exclamation text-red-400"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Nama Lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                   required autofocus autocomplete="name"
                   class="w-full border border-slate-200 rounded-2xl px-4 h-11 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent placeholder:text-slate-400 transition @error('name') border-red-400 bg-red-50 @enderror"
                   placeholder="Nama Anda">
        </div>

        <div>
            <label for="email" class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   required autocomplete="username"
                   class="w-full border border-slate-200 rounded-2xl px-4 h-11 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent placeholder:text-slate-400 transition @error('email') border-red-400 bg-red-50 @enderror"
                   placeholder="nama@email.com">
        </div>

        <div>
            <label for="password" class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Password</label>
            <input id="password" type="password" name="password"
                   required autocomplete="new-password"
                   class="w-full border border-slate-200 rounded-2xl px-4 h-11 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent placeholder:text-slate-400 transition @error('password') border-red-400 bg-red-50 @enderror"
                   placeholder="Minimal 8 karakter">
        </div>

        <div>
            <label for="password_confirmation" class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   required autocomplete="new-password"
                   class="w-full border border-slate-200 rounded-2xl px-4 h-11 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent placeholder:text-slate-400 transition @error('password_confirmation') border-red-400 bg-red-50 @enderror"
                   placeholder="Ulangi password">
        </div>

        <button type="submit"
                class="w-full h-11 bg-slate-900 hover:bg-black text-white font-semibold text-sm rounded-full transition flex items-center justify-center gap-x-2 mt-2">
            <i class="fa-solid fa-user-plus text-xs"></i>
            Buat Akun
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-teal-600 hover:text-teal-700 transition">Masuk di sini</a>
    </p>

</x-guest-layout>
