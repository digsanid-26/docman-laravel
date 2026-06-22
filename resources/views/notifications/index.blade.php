<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-900 tracking-tight">Notifikasi</h2>
            @if(auth()->user()->unreadNotifications()->count() > 0)
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    <button type="submit"
                            class="flex items-center gap-x-1.5 text-xs font-semibold text-teal-600 hover:text-teal-700 transition">
                        <i class="fa-solid fa-check-double text-[10px]"></i> Tandai semua dibaca
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6">

            @if(session('success'))
                <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-2xl flex items-center gap-x-2">
                    <i class="fa-solid fa-check-circle text-emerald-500"></i> {{ session('success') }}
                </div>
            @endif

            <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden" style="box-shadow:0 1px 3px rgba(15,23,42,0.05)">

                @forelse($notifications as $notif)
                @php
                    $data = $notif->data;
                    $iconMap = [
                        'document_submitted'    => ['fa-paper-plane',           'bg-blue-100 text-blue-600'],
                        'document_under_review' => ['fa-eye',                   'bg-amber-100 text-amber-600'],
                        'document_needs_revision'=> ['fa-exclamation-triangle', 'bg-orange-100 text-orange-600'],
                        'document_approved'     => ['fa-check-circle',          'bg-emerald-100 text-emerald-600'],
                        'document_rejected'     => ['fa-times-circle',          'bg-red-100 text-red-600'],
                    ];
                    [$icon, $iconCls] = $iconMap[$data['type'] ?? ''] ?? ['fa-bell', 'bg-slate-100 text-slate-600'];
                    $isUnread = is_null($notif->read_at);
                @endphp
                <div class="flex items-start gap-x-4 px-5 py-4 border-b border-slate-100 last:border-none {{ $isUnread ? 'bg-teal-50/40' : '' }} hover:bg-slate-50 transition">
                    <div class="w-9 h-9 rounded-2xl {{ $iconCls }} flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fa-solid {{ $icon }} text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-slate-800 {{ $isUnread ? 'font-medium' : '' }}">
                            {{ $data['message'] ?? 'Notifikasi baru' }}
                        </p>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $notif->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex items-center gap-x-2 flex-shrink-0">
                        @if($isUnread)
                            <div class="w-2 h-2 bg-teal-500 rounded-full"></div>
                        @endif
                        <form method="POST" action="{{ route('notifications.read', $notif->id) }}">
                            @csrf
                            <button type="submit"
                                    class="text-xs text-teal-600 hover:text-teal-700 font-medium transition"
                                    title="{{ $isUnread ? 'Tandai dibaca & buka' : 'Buka' }}">
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="px-6 py-16 text-center">
                    <i class="fa-solid fa-bell-slash text-4xl text-slate-200 mb-3 block"></i>
                    <p class="text-slate-500 text-sm">Belum ada notifikasi.</p>
                </div>
                @endforelse

            </div>

            @if($notifications->hasPages())
            <div class="flex justify-center gap-x-1 mt-5 text-xs">
                @if(!$notifications->onFirstPage())
                    <a href="{{ $notifications->previousPageUrl() }}" class="px-3 py-1.5 border border-slate-200 rounded-2xl font-medium hover:bg-white transition">Sebelumnya</a>
                @endif
                @if($notifications->hasMorePages())
                    <a href="{{ $notifications->nextPageUrl() }}" class="px-3 py-1.5 border border-slate-200 rounded-2xl font-medium hover:bg-white transition">Selanjutnya</a>
                @endif
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
