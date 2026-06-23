<x-guest-layout>

    <div class="mb-8">
        <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Create an account</h1>
        <p class="text-sm text-slate-500 mt-1">Sign up and start managing your documents</p>
    </div>

    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-2xl flex items-center gap-x-2">
            <i class="fa-solid fa-circle-exclamation text-red-400 flex-shrink-0"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-4" id="register-form">
        @csrf

        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

        <div>
            <label for="name" class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                   required autofocus autocomplete="name"
                   class="w-full border border-slate-200 rounded-2xl px-4 h-11 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent placeholder:text-slate-400 transition @error('name') border-red-400 bg-red-50 @enderror"
                   placeholder="Your full name">
        </div>

        <div>
            <label for="email" class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   required autocomplete="username"
                   class="w-full border border-slate-200 rounded-2xl px-4 h-11 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent placeholder:text-slate-400 transition @error('email') border-red-400 bg-red-50 @enderror"
                   placeholder="name@email.com">
        </div>

        <div>
            <label for="password" class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Password</label>
            <input id="password" type="password" name="password"
                   required autocomplete="new-password"
                   class="w-full border border-slate-200 rounded-2xl px-4 h-11 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent placeholder:text-slate-400 transition @error('password') border-red-400 bg-red-50 @enderror"
                   placeholder="Minimum 8 characters">
        </div>

        <div>
            <label for="password_confirmation" class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   required autocomplete="new-password"
                   class="w-full border border-slate-200 rounded-2xl px-4 h-11 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent placeholder:text-slate-400 transition @error('password_confirmation') border-red-400 bg-red-50 @enderror"
                   placeholder="Repeat your password">
        </div>

        @if(config('services.recaptcha.site_key'))
            <p class="text-[11px] text-slate-400 leading-relaxed">
                Protected by reCAPTCHA —
                <a href="https://policies.google.com/privacy" target="_blank" class="underline hover:text-slate-600">Privacy</a> &
                <a href="https://policies.google.com/terms" target="_blank" class="underline hover:text-slate-600">Terms</a>
            </p>
        @endif

        <button type="submit" id="register-btn"
                class="w-full h-11 bg-slate-900 hover:bg-black text-white font-semibold text-sm rounded-full transition flex items-center justify-center gap-x-2 mt-2">
            <i class="fa-solid fa-user-plus text-xs"></i>
            <span>Create Account</span>
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
        Already have an account?
        <a href="{{ route('login') }}" class="font-semibold text-red-600 hover:text-red-700 transition">Sign in here</a>
    </p>

</x-guest-layout>

@if(config('services.recaptcha.site_key'))
@push('scripts')
<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
<script>
    document.getElementById('register-form').addEventListener('submit', function (e) {
        e.preventDefault();
        var form = this;
        var btn  = document.getElementById('register-btn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i><span>Verifying…</span>';

        grecaptcha.ready(function () {
            grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', { action: 'register' })
                .then(function (token) {
                    document.getElementById('g-recaptcha-response').value = token;
                    form.submit();
                })
                .catch(function () {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-user-plus text-xs"></i><span>Create Account</span>';
                });
        });
    });
</script>
@endpush
@endif
