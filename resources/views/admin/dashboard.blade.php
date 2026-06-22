<x-admin-layout title="Dashboard">

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
        @foreach([
            ['label' => 'Total Dokumen', 'value' => $stats['total'],          'color' => 'border-slate-300',  'text' => 'text-slate-800',   'bg' => 'bg-white'],
            ['label' => 'Dikirim',        'value' => $stats['submitted'],      'color' => 'border-blue-300',   'text' => 'text-blue-700',    'bg' => 'bg-blue-50'],
            ['label' => 'Direview',       'value' => $stats['under_review'],   'color' => 'border-amber-300',  'text' => 'text-amber-700',   'bg' => 'bg-amber-50'],
            ['label' => 'Perlu Revisi',   'value' => $stats['needs_revision'], 'color' => 'border-orange-300', 'text' => 'text-orange-700',  'bg' => 'bg-orange-50'],
            ['label' => 'Disetujui',      'value' => $stats['approved'],       'color' => 'border-emerald-300','text' => 'text-emerald-700', 'bg' => 'bg-emerald-50'],
            ['label' => 'Ditolak',        'value' => $stats['rejected'],       'color' => 'border-red-300',    'text' => 'text-red-700',     'bg' => 'bg-red-50'],
        ] as $card)
        <div class="{{ $card['bg'] }} border-l-4 {{ $card['color'] }} rounded-2xl p-4">
            <div class="text-3xl font-bold {{ $card['text'] }} tracking-tight">{{ $card['value'] }}</div>
            <div class="text-xs text-slate-500 mt-1 font-medium">{{ $card['label'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- Recent Documents Table --}}
    <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden" style="box-shadow:0 1px 3px rgba(15,23,42,0.05)">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <div>
                <h2 class="font-semibold text-slate-900">10 Dokumen Terbaru</h2>
                <p class="text-xs text-slate-400 mt-0.5">Semua dokumen yang baru masuk</p>
            </div>
            <a href="{{ route('admin.documents.index') }}"
               class="flex items-center gap-x-1.5 text-xs font-semibold text-teal-600 hover:text-teal-700 transition">
                Lihat Semua <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase text-[11px] tracking-wide font-semibold">
                        <th class="px-6 py-3.5 text-left">Judul</th>
                        <th class="px-6 py-3.5 text-left">Pengirim</th>
                        <th class="px-6 py-3.5 text-center">Status</th>
                        <th class="px-6 py-3.5 text-left">Dikirim</th>
                        <th class="px-6 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recent as $doc)
                    <tr class="table-row">
                        <td class="px-6 py-3.5">
                            <div class="font-medium text-slate-800">{{ Str::limit($doc->title, 45) }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">{{ $doc->document_type }}</div>
                        </td>
                        <td class="px-6 py-3.5 text-slate-600">{{ $doc->user->name }}</td>
                        <td class="px-6 py-3.5 text-center">
                            @php
                                $badgeMap = [
                                    'SUBMITTED'      => ['bg-blue-100 text-blue-700',    'fa-paper-plane',          'Dikirim'],
                                    'UNDER_REVIEW'   => ['bg-amber-100 text-amber-700',  'fa-eye',                  'Direview'],
                                    'NEEDS_REVISION' => ['bg-orange-100 text-orange-700','fa-exclamation-triangle', 'Perlu Revisi'],
                                    'APPROVED'       => ['bg-emerald-100 text-emerald-700','fa-check-circle',       'Disetujui'],
                                    'REJECTED'       => ['bg-red-100 text-red-700',      'fa-times-circle',         'Ditolak'],
                                ];
                                [$cls, $ico, $lbl] = $badgeMap[$doc->status] ?? ['bg-slate-100 text-slate-600','fa-circle','?'];
                            @endphp
                            <span class="inline-flex items-center gap-x-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $cls }}">
                                <i class="fa-solid {{ $ico }} text-[10px]"></i> {{ $lbl }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5 text-xs text-slate-500">{{ $doc->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-3.5 text-center">
                            <a href="{{ route('admin.documents.show', $doc) }}"
                               class="action-btn inline-flex items-center gap-x-1.5 px-3 py-1.5 text-xs bg-white border border-slate-200 hover:bg-slate-50 rounded-2xl font-medium text-slate-700">
                                <i class="fa-solid fa-eye text-[10px]"></i> Review
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-sm">Belum ada dokumen.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-admin-layout>
