<x-admin-layout title="Review Dokumen">

    {{-- Back + breadcrumb --}}
    <div class="flex items-center gap-x-2 text-sm text-slate-500 mb-5">
        <a href="{{ route('admin.documents.index') }}" class="hover:text-slate-800 transition flex items-center gap-x-1.5">
            <i class="fa-solid fa-arrow-left text-xs"></i> Daftar Dokumen
        </a>
        <span class="text-slate-300">/</span>
        <span class="text-slate-800 font-medium truncate max-w-xs">{{ Str::limit($document->title, 40) }}</span>
    </div>

    <div class="grid lg:grid-cols-3 gap-5">

        {{-- LEFT: Document Info --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Document Card --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 space-y-5" style="box-shadow:0 1px 3px rgba(15,23,42,0.05)">
                <div class="flex justify-between items-start gap-x-4">
                    <div class="min-w-0">
                        <h2 class="text-xl font-semibold text-slate-900 leading-tight">{{ $document->title }}</h2>
                        <p class="text-sm text-slate-500 mt-1">
                            <span class="inline-flex items-center gap-x-1">
                                <i class="fa-solid fa-user text-[10px] text-slate-400"></i>
                                {{ $document->user->name }}
                            </span>
                            <span class="mx-1 text-slate-300">·</span>
                            {{ $document->user->email }}
                        </p>
                    </div>
                    @php
                        $badgeMap = [
                            'SUBMITTED'      => 'bg-blue-100 text-blue-700',
                            'UNDER_REVIEW'   => 'bg-amber-100 text-amber-700',
                            'NEEDS_REVISION' => 'bg-orange-100 text-orange-700',
                            'APPROVED'       => 'bg-emerald-100 text-emerald-700',
                            'REJECTED'       => 'bg-red-100 text-red-700',
                        ];
                        $iconMap = [
                            'SUBMITTED'      => 'fa-paper-plane',
                            'UNDER_REVIEW'   => 'fa-eye',
                            'NEEDS_REVISION' => 'fa-exclamation-triangle',
                            'APPROVED'       => 'fa-check-circle',
                            'REJECTED'       => 'fa-times-circle',
                        ];
                    @endphp
                    <span class="flex-shrink-0 inline-flex items-center gap-x-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $badgeMap[$document->status] ?? '' }}">
                        <i class="fa-solid {{ $iconMap[$document->status] ?? 'fa-circle' }} text-[10px]"></i>
                        {{ $document->statusLabel() }}
                    </span>
                </div>

                <div class="h-px bg-slate-100"></div>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase tracking-wide mb-1">Jenis Dokumen</p>
                        <p class="font-medium text-slate-800">{{ $document->document_type }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase tracking-wide mb-1">Tanggal Dokumen</p>
                        <p class="font-medium text-slate-800">{{ $document->document_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase tracking-wide mb-1">Dikirim</p>
                        <p class="font-medium text-slate-800">{{ $document->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @if($document->approved_at)
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase tracking-wide mb-1">Disetujui</p>
                        <p class="font-medium text-emerald-700">{{ $document->approved_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                </div>

                <div>
                    <p class="text-xs text-slate-400 font-medium uppercase tracking-wide mb-2">Deskripsi</p>
                    <p class="text-slate-700 bg-slate-50 rounded-2xl px-4 py-3 text-sm leading-relaxed">{{ $document->description }}</p>
                </div>

                <div class="flex gap-x-3">
                    <a href="{{ route('admin.documents.download', $document) }}"
                       class="action-btn inline-flex items-center gap-x-2 px-4 h-10 bg-white border border-slate-200 hover:bg-slate-50 text-sm font-semibold rounded-3xl text-slate-700 transition">
                        <i class="fa-solid fa-download text-slate-400"></i> Download File
                    </a>
                </div>
            </div>

            {{-- Review History --}}
            @if($reviews->isNotEmpty())
            <div class="bg-white border border-slate-100 rounded-3xl p-6" style="box-shadow:0 1px 3px rgba(15,23,42,0.05)">
                <h4 class="font-semibold text-slate-900 mb-4 flex items-center gap-x-2">
                    <i class="fa-solid fa-clock-rotate-left text-slate-400 text-sm"></i> Riwayat Review
                </h4>
                <div class="space-y-3">
                    @foreach($reviews as $review)
                    <div class="border border-slate-100 rounded-2xl p-4">
                        <div class="flex justify-between items-center mb-2">
                            <div class="flex items-center gap-x-2">
                                <div class="w-6 h-6 bg-teal-100 rounded-full flex items-center justify-center text-[10px] font-bold text-teal-700">
                                    {{ strtoupper(substr($review->admin->name, 0, 1)) }}
                                </div>
                                <span class="text-sm font-medium text-slate-700">{{ $review->admin->name }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $review->actionBadge() }}">
                                    {{ $review->actionLabel() }}
                                </span>
                            </div>
                            <span class="text-xs text-slate-400">{{ $review->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($review->notes)
                            <p class="text-sm text-slate-600 bg-slate-50 rounded-xl px-3 py-2 mt-2">{{ $review->notes }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- RIGHT: Decision Panel --}}
        <div class="lg:col-span-1">
            @if(!$document->isFinal())
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sticky top-24" style="box-shadow:0 1px 3px rgba(15,23,42,0.05)">
                <h4 class="font-semibold text-slate-900 mb-5 flex items-center gap-x-2">
                    <i class="fa-solid fa-gavel text-slate-400 text-sm"></i> Berikan Keputusan
                </h4>

                <form method="POST" action="{{ route('admin.documents.review', $document) }}"
                      onsubmit="return confirm('Yakin ingin melanjutkan aksi ini?')">
                    @csrf

                    <div class="space-y-4">
                        {{-- Action selector --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Aksi <span class="text-red-500">*</span></label>
                            <div class="space-y-2">
                                @foreach([
                                    ['value' => 'needs_revision', 'label' => 'Perlu Revisi',  'icon' => 'fa-exclamation-triangle', 'checked' => 'border-orange-500 bg-orange-50', 'unchecked' => 'border-slate-200 hover:border-orange-300'],
                                    ['value' => 'approved',       'label' => 'Setujui',        'icon' => 'fa-check-circle',        'checked' => 'border-emerald-500 bg-emerald-50', 'unchecked' => 'border-slate-200 hover:border-emerald-300'],
                                    ['value' => 'rejected',       'label' => 'Tolak',          'icon' => 'fa-times-circle',        'checked' => 'border-red-500 bg-red-50', 'unchecked' => 'border-slate-200 hover:border-red-300'],
                                ] as $act)
                                <label class="flex items-center gap-x-3 p-3 border-2 rounded-2xl cursor-pointer transition {{ old('action') === $act['value'] ? $act['checked'] : $act['unchecked'] }} has-[:checked]:{{ $act['checked'] }}">
                                    <input type="radio" name="action" value="{{ $act['value'] }}" class="hidden"
                                           {{ old('action') === $act['value'] ? 'checked' : '' }}
                                           onchange="this.closest('form').querySelectorAll('label').forEach(l => l.classList.remove(...'{{ $act['checked'] }}'.split(' '))); this.closest('label').classList.add(...'{{ $act['checked'] }}'.split(' '))">
                                    <i class="fa-solid {{ $act['icon'] }} text-sm text-slate-400 w-4 text-center"></i>
                                    <span class="text-sm font-medium text-slate-700">{{ $act['label'] }}</span>
                                </label>
                                @endforeach
                            </div>
                            @error('action') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Notes --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">
                                Catatan <span class="text-red-500">*</span>
                            </label>
                            <textarea name="notes" rows="5"
                                      class="w-full border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-1 focus:ring-teal-500 focus:border-teal-500 placeholder:text-slate-400 resize-none @error('notes') border-red-400 @enderror"
                                      placeholder="Tuliskan catatan atau alasan keputusan...">{{ old('notes') }}</textarea>
                            @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit"
                                class="w-full h-11 bg-slate-900 hover:bg-black text-white font-semibold text-sm rounded-3xl transition flex items-center justify-center gap-x-2">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                            Kirim Keputusan
                        </button>
                        <p class="text-[11px] text-slate-400 text-center">Email notifikasi otomatis dikirim ke user</p>
                    </div>
                </form>
            </div>
            @else
            <div class="bg-slate-50 border border-slate-200 rounded-3xl p-6 text-center">
                <i class="fa-solid fa-circle-check text-3xl {{ $document->status === 'APPROVED' ? 'text-emerald-400' : 'text-slate-300' }} mb-3"></i>
                <p class="font-semibold text-slate-700 text-sm">Dokumen Sudah Final</p>
                <p class="text-xs text-slate-500 mt-1">Status: {{ $document->statusLabel() }}</p>
                @if($document->approved_at)
                    <p class="text-xs text-emerald-600 mt-2">Disetujui {{ $document->approved_at->format('d/m/Y H:i') }}</p>
                @endif
            </div>
            @endif
        </div>

    </div>

</x-admin-layout>
