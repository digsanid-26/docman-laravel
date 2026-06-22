<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('documents.index') }}" class="text-gray-400 hover:text-gray-600">← Kembali</a>
            <h2 class="font-semibold text-xl text-gray-800">Detail Dokumen</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if($document->status === 'NEEDS_REVISION')
                <div class="bg-red-50 border border-red-200 rounded-xl p-5">
                    <div class="flex items-start gap-3">
                        <span class="text-red-500 text-xl">⚠️</span>
                        <div>
                            <p class="font-semibold text-red-800 mb-1">Dokumen Perlu Revisi</p>
                            <p class="text-red-700 text-sm">{{ $document->admin_notes }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($document->status === 'APPROVED')
                <div class="bg-green-50 border border-green-200 rounded-xl p-5 flex items-center gap-3">
                    <span class="text-green-500 text-xl">✅</span>
                    <p class="text-green-800 font-medium">Dokumen disetujui pada {{ $document->approved_at?->format('d/m/Y H:i') }}</p>
                </div>
            @endif

            @if($document->status === 'REJECTED')
                <div class="bg-gray-100 border border-gray-300 rounded-xl p-5">
                    <div class="flex items-start gap-3">
                        <span class="text-gray-500 text-xl">❌</span>
                        <div>
                            <p class="font-semibold text-gray-700 mb-1">Dokumen Ditolak</p>
                            <p class="text-gray-600 text-sm">{{ $document->admin_notes }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">{{ $document->title }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $document->document_type }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $document->statusBadge() }}">
                        {{ $document->statusLabel() }}
                    </span>
                </div>

                <hr class="border-gray-100">

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Tanggal Dokumen</p>
                        <p class="font-medium text-gray-800">{{ $document->document_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Tanggal Dikirim</p>
                        <p class="font-medium text-gray-800">{{ $document->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <div class="text-sm">
                    <p class="text-gray-500 mb-1">Deskripsi</p>
                    <p class="text-gray-800 bg-gray-50 rounded-lg p-3">{{ $document->description }}</p>
                </div>
            </div>

            @if($reviews->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h4 class="font-semibold text-gray-800 mb-4">Riwayat Review</h4>
                    <div class="space-y-4">
                        @foreach($reviews as $review)
                        <div class="border border-gray-100 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium text-gray-700">{{ $review->admin->name }}</span>
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $review->actionBadge() }}">
                                        {{ $review->actionLabel() }}
                                    </span>
                                </div>
                                <span class="text-xs text-gray-400">{{ $review->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            @if($review->notes)
                                <p class="text-sm text-gray-600 bg-gray-50 rounded p-2">{{ $review->notes }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
