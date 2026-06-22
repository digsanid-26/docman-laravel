<x-admin-layout title="Configuration">

<div x-data="{ tab: '{{ $tab }}' }" class="space-y-6">

    {{-- Tab Bar --}}
    <div class="flex gap-x-1 bg-white border border-slate-100 rounded-2xl p-1 w-fit" style="box-shadow:0 1px 3px rgba(15,23,42,0.05)">
        <button @click="tab='gmail'"
                :class="tab==='gmail' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                class="px-5 py-2 rounded-xl text-sm font-medium transition">
            <i class="fa-brands fa-google mr-1.5"></i>Gmail
        </button>
        <button @click="tab='account'"
                :class="tab==='account' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                class="px-5 py-2 rounded-xl text-sm font-medium transition">
            <i class="fa-solid fa-user-cog mr-1.5"></i>Account
        </button>
        <button @click="tab='templates'"
                :class="tab==='templates' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                class="px-5 py-2 rounded-xl text-sm font-medium transition">
            <i class="fa-solid fa-envelope-open-text mr-1.5"></i>Email Templates
        </button>
    </div>

    {{-- ===================== TAB: GMAIL ===================== --}}
    <div x-show="tab==='gmail'" x-cloak>
        <div class="max-w-2xl space-y-6">

            <div>
                <h2 class="font-semibold text-xl text-slate-900">Gmail OAuth2 — Email Setup</h2>
                <p class="text-sm text-slate-500 mt-1">Connect your Gmail account via Google Cloud Console credentials to send notification emails.</p>
            </div>

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-2xl text-sm">
                    <i class="fa-solid fa-triangle-exclamation mr-2"></i>{{ session('error') }}
                </div>
            @endif

            <div class="bg-white border border-slate-100 rounded-3xl p-6 space-y-5" style="box-shadow:0 1px 3px rgba(15,23,42,0.05)">

                <div class="flex items-center gap-x-3">
                    @if($gmailConfigured)
                        <div class="w-10 h-10 bg-emerald-100 rounded-2xl flex items-center justify-center">
                            <i class="fa-solid fa-check-circle text-emerald-500"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800">Gmail connected</p>
                            <p class="text-xs text-slate-500">Sending from: <strong>{{ config('mail.from.address') }}</strong></p>
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

                <ol class="space-y-4 text-sm">
                    <li class="flex gap-x-3">
                        <span class="w-6 h-6 rounded-full bg-slate-900 text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">1</span>
                        <div>
                            <p class="font-medium text-slate-800">Create Google Cloud credentials</p>
                            <p class="text-slate-500 mt-0.5">Go to <a href="https://console.cloud.google.com/apis/credentials" target="_blank" class="text-red-600 hover:underline font-medium">console.cloud.google.com/apis/credentials</a>, create an <strong>OAuth 2.0 Client ID</strong> (Web application type), and add the redirect URI below to "Authorized redirect URIs".</p>
                            <div class="mt-2 font-mono text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 select-all">{{ route('admin.gmail.callback') }}</div>
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
                            <p class="text-slate-500 mt-0.5">Click the button below to start the OAuth2 flow.</p>
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
                            <p class="text-slate-500 mt-0.5">After authorization, the refresh token will appear here. Copy it as <code>GOOGLE_REFRESH_TOKEN</code> in your <code>.env</code>, then run <code>php artisan config:clear</code>.</p>
                            @isset($refreshToken)
                                <div class="mt-2">
                                    <p class="text-xs font-semibold text-emerald-700 mb-1"><i class="fa-solid fa-check-circle mr-1"></i>Authorization successful! Copy your refresh token:</p>
                                    <div class="font-mono text-xs bg-emerald-50 border border-emerald-200 rounded-xl px-3 py-3 break-all select-all">{{ $refreshToken }}</div>
                                </div>
                            @endisset
                        </div>
                    </li>
                </ol>
            </div>
        </div>
    </div>

    {{-- ===================== TAB: ACCOUNT ===================== --}}
    <div x-show="tab==='account'" x-cloak>
        <div class="max-w-xl space-y-6">

            <div>
                <h2 class="font-semibold text-xl text-slate-900">Account Settings</h2>
                <p class="text-sm text-slate-500 mt-1">Update admin name, email address, and password.</p>
            </div>

            @if(session('success_account'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-sm">
                    <i class="fa-solid fa-check-circle mr-2"></i>{{ session('success_account') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-2xl text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.config.account') }}" method="POST"
                  class="bg-white border border-slate-100 rounded-3xl p-6 space-y-5" style="box-shadow:0 1px 3px rgba(15,23,42,0.05)">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $admin->name) }}"
                           class="w-full border-slate-200 rounded-xl text-sm focus:ring-red-500 focus:border-red-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $admin->email) }}"
                           class="w-full border-slate-200 rounded-xl text-sm focus:ring-red-500 focus:border-red-500">
                </div>

                <div class="h-px bg-slate-100"></div>

                <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">Change Password <span class="normal-case">(leave blank to keep current)</span></p>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">New Password</label>
                    <input type="password" name="password" autocomplete="new-password"
                           class="w-full border-slate-200 rounded-xl text-sm focus:ring-red-500 focus:border-red-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Confirm New Password</label>
                    <input type="password" name="password_confirmation" autocomplete="new-password"
                           class="w-full border-slate-200 rounded-xl text-sm focus:ring-red-500 focus:border-red-500">
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-2xl transition">
                        Save Account Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===================== TAB: EMAIL TEMPLATES ===================== --}}
    <div x-show="tab==='templates'" x-cloak>
        <div class="max-w-3xl space-y-6">

            <div>
                <h2 class="font-semibold text-xl text-slate-900">Email Templates</h2>
                <p class="text-sm text-slate-500 mt-1">
                    Customize the subject and body for each notification email.
                    Available placeholders:
                    <code class="bg-slate-100 px-1 rounded">{name}</code>
                    <code class="bg-slate-100 px-1 rounded">{document_title}</code>
                    <code class="bg-slate-100 px-1 rounded">{document_type}</code>
                    <code class="bg-slate-100 px-1 rounded">{sender_name}</code>
                    <code class="bg-slate-100 px-1 rounded">{notes}</code>
                </p>
            </div>

            @if(session('success_templates'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-sm">
                    <i class="fa-solid fa-check-circle mr-2"></i>{{ session('success_templates') }}
                </div>
            @endif

            <form action="{{ route('admin.config.templates') }}" method="POST" class="space-y-5">
                @csrf
                @method('PATCH')

                @foreach($types as $type => $label)
                    <div class="bg-white border border-slate-100 rounded-3xl p-5 space-y-3" style="box-shadow:0 1px 3px rgba(15,23,42,0.05)">
                        <p class="font-semibold text-slate-800 text-sm">{{ $label }}</p>

                        <div>
                            <label class="block text-xs text-slate-500 mb-1">Subject</label>
                            <input type="text"
                                   name="templates[{{ $type }}][subject]"
                                   value="{{ old("templates.{$type}.subject", $templates[$type]['subject']) }}"
                                   class="w-full border-slate-200 rounded-xl text-sm focus:ring-red-500 focus:border-red-500">
                        </div>

                        <div>
                            <label class="block text-xs text-slate-500 mb-1">Body <span class="text-slate-400">(supports **bold**, *italic* markdown)</span></label>
                            <textarea name="templates[{{ $type }}][body]"
                                      rows="4"
                                      class="w-full border-slate-200 rounded-xl text-sm focus:ring-red-500 focus:border-red-500 font-mono">{{ old("templates.{$type}.body", $templates[$type]['body']) }}</textarea>
                        </div>
                    </div>
                @endforeach

                <div>
                    <button type="submit"
                            class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-2xl transition">
                        Save Email Templates
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

</x-admin-layout>
