<x-admin-layout title="Gmail OAuth2 Setup">

    <div class="max-w-2xl mx-auto space-y-6">

        <div>
            <h2 class="font-semibold text-xl text-slate-900">Gmail OAuth2 — Email Setup</h2>
            <p class="text-sm text-slate-500 mt-1">
                Connect your Gmail account via Google Cloud Console credentials to send notification emails.
            </p>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-2xl text-sm">
                <i class="fa-solid fa-triangle-exclamation mr-2"></i>{{ session('error') }}
            </div>
        @endif

        {{-- Status card --}}
        <div class="bg-white border border-slate-100 rounded-3xl p-6 space-y-5" style="box-shadow:0 1px 3px rgba(15,23,42,0.05)">

            <div class="flex items-center gap-x-3">
                @if($configured)
                    <div class="w-10 h-10 bg-emerald-100 rounded-2xl flex items-center justify-center">
                        <i class="fa-solid fa-check-circle text-emerald-500"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800">Gmail connected</p>
                        <p class="text-xs text-slate-500">Sending from: <strong>{{ $fromEmail }}</strong></p>
                    </div>
                @else
                    <div class="w-10 h-10 bg-slate-100 rounded-2xl flex items-center justify-center">
                        <i class="fa-solid fa-envelope-circle-check text-slate-400"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800">Not connected</p>
                        <p class="text-xs text-slate-500">Complete the steps below to enable email notifications.</p>
                    </div>
                @endif
            </div>

            <div class="h-px bg-slate-100"></div>

            {{-- Steps --}}
            <ol class="space-y-4 text-sm">
                <li class="flex gap-x-3">
                    <span class="w-6 h-6 rounded-full bg-slate-900 text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">1</span>
                    <div>
                        <p class="font-medium text-slate-800">Create Google Cloud credentials</p>
                        <p class="text-slate-500 mt-0.5">
                            Go to
                            <a href="https://console.cloud.google.com/apis/credentials" target="_blank"
                               class="text-red-600 hover:underline font-medium">console.cloud.google.com/apis/credentials</a>,
                            create an <strong>OAuth 2.0 Client ID</strong> (Web application type), and add the
                            redirect URI below to "Authorized redirect URIs".
                        </p>
                        <div class="mt-2 font-mono text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 select-all">
                            {{ route('admin.gmail.callback') }}
                        </div>
                    </div>
                </li>
                <li class="flex gap-x-3">
                    <span class="w-6 h-6 rounded-full bg-slate-900 text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">2</span>
                    <div>
                        <p class="font-medium text-slate-800">Add credentials to <code>.env</code></p>
                        <div class="mt-2 font-mono text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 space-y-1">
                            <p>MAIL_MAILER=gmail</p>
                            <p>MAIL_FROM_ADDRESS=your-gmail@gmail.com</p>
                            <p>GOOGLE_CLIENT_ID=&lt;your-client-id&gt;</p>
                            <p>GOOGLE_CLIENT_SECRET=&lt;your-client-secret&gt;</p>
                            <p>GOOGLE_REFRESH_TOKEN=  &lt;— filled in step 4&gt;</p>
                        </div>
                    </div>
                </li>
                <li class="flex gap-x-3">
                    <span class="w-6 h-6 rounded-full bg-slate-900 text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">3</span>
                    <div>
                        <p class="font-medium text-slate-800">Authorize with Google</p>
                        <p class="text-slate-500 mt-0.5">Click the button below to start the OAuth2 flow. You will be redirected to Google's consent screen.</p>
                        <a href="{{ route('admin.gmail.redirect') }}"
                           class="mt-3 inline-flex items-center gap-x-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-2xl transition">
                            <i class="fa-brands fa-google text-xs"></i>
                            Authorize Gmail Access
                        </a>
                    </div>
                </li>
                <li class="flex gap-x-3">
                    <span class="w-6 h-6 rounded-full bg-slate-900 text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">4</span>
                    <div>
                        <p class="font-medium text-slate-800">Copy the Refresh Token into <code>.env</code></p>
                        <p class="text-slate-500 mt-0.5">After authorization, the refresh token appears below. Add it as <code>GOOGLE_REFRESH_TOKEN</code> in your <code>.env</code> and restart the server.</p>
                        @isset($refreshToken)
                            <div class="mt-2">
                                <p class="text-xs font-semibold text-emerald-700 mb-1"><i class="fa-solid fa-check-circle mr-1"></i>Authorization successful! Copy your refresh token:</p>
                                <div class="font-mono text-xs bg-emerald-50 border border-emerald-200 rounded-xl px-3 py-3 break-all select-all">
                                    {{ $refreshToken }}
                                </div>
                            </div>
                        @endisset
                    </div>
                </li>
            </ol>
        </div>

    </div>

</x-admin-layout>
