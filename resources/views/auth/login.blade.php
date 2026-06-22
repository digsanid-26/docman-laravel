<x-guest-layout>

    <div class="mb-8">
        <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Selamat datang kembali</h1>
        <p class="text-sm text-slate-500 mt-1">Masuk ke akun DMS Docman Anda</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-2xl flex items-center gap-x-2">
            <i class="fa-solid fa-circle-exclamation text-red-400"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   required autofocus autocomplete="username"
                   class="w-full border border-slate-200 rounded-2xl px-4 h-11 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent placeholder:text-slate-400 transition @error('email') border-red-400 bg-red-50 @enderror"
                   placeholder="nama@email.com">
        </div>

        <div>
            <div class="flex justify-between items-center mb-1.5">
                <label for="password" class="block text-xs font-semibold text-slate-500 uppercase tracking-wide">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs text-teal-600 hover:text-teal-700 transition">Lupa password?</a>
                @endif
            </div>
            <input id="password" type="password" name="password"
                   required autocomplete="current-password"
                   class="w-full border border-slate-200 rounded-2xl px-4 h-11 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent placeholder:text-slate-400 transition @error('password') border-red-400 bg-red-50 @enderror"
                   placeholder="••••••••">
        </div>

        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="flex items-center gap-x-2 cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember"
                       class="w-4 h-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500 accent-teal-600">
                <span class="text-xs text-slate-600">Ingat saya</span>
            </label>
        </div>

        <button type="submit"
                class="w-full h-11 bg-slate-900 hover:bg-black text-white font-semibold text-sm rounded-full transition flex items-center justify-center gap-x-2 mt-2">
            <i class="fa-solid fa-right-to-bracket text-xs"></i>
            Masuk ke Dashboard
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-semibold text-teal-600 hover:text-teal-700 transition">Daftar gratis</a>
    </p>

</x-guest-layout>
