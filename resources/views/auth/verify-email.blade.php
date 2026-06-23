<x-guest-layout>

    <div class="mb-8 text-center">
        <div class="w-16 h-16 bg-red-50 border border-red-100 rounded-3xl flex items-center justify-center mx-auto mb-5">
            <i class="fa-solid fa-envelope-open text-red-500 text-2xl"></i>
        </div>
        <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Verify your email</h1>
        <p class="text-sm text-slate-500 mt-2 leading-relaxed">
            We sent a verification link to <strong>{{ auth()->user()->email }}</strong>.<br>
            Click the link in that email to activate your account.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-sm flex items-center gap-x-2">
            <i class="fa-solid fa-check-circle text-emerald-500 flex-shrink-0"></i>
            A new verification link has been sent to your email address.
        </div>
    @endif

    <div class="bg-slate-50 border border-slate-100 rounded-2xl px-4 py-4 text-xs text-slate-500 space-y-1 mb-6">
        <p><i class="fa-solid fa-lightbulb text-amber-400 mr-1.5"></i>Check your <strong>spam / junk</strong> folder if you don't see the email.</p>
        <p><i class="fa-solid fa-clock text-slate-400 mr-1.5"></i>The link expires after <strong>60 minutes</strong>.</p>
    </div>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit"
                class="w-full h-11 bg-slate-900 hover:bg-black text-white font-semibold text-sm rounded-full transition flex items-center justify-center gap-x-2">
            <i class="fa-solid fa-paper-plane text-xs"></i>
            Resend Verification Email
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-4 text-center">
        @csrf
        <button type="submit" class="text-sm text-slate-400 hover:text-slate-600 transition underline">
            Sign out and use a different account
        </button>
    </form>

</x-guest-layout>
