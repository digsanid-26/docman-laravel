<x-admin-layout title="Approved Documents">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-y-4 mb-5">
        <div>
            <h2 class="font-semibold text-xl text-slate-900">Approved Documents</h2>
            <p class="text-xs text-slate-500 mt-0.5">
                {{ $documents->total() }} approved document(s)
            </p>
        </div>

        {{-- Search --}}
        <form method="GET" action="{{ route('admin.approved.index') }}" class="flex items-center gap-x-2">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ $search }}"
                       placeholder="Search title or sender…"
                       class="pl-9 pr-4 h-10 w-64 border border-slate-200 rounded-3xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
            </div>
            <button type="submit"
                    class="h-10 px-5 bg-slate-900 hover:bg-black text-white text-sm font-semibold rounded-3xl transition">
                Search
            </button>
            @if($search)
                <a href="{{ route('admin.approved.index') }}"
                   class="h-10 px-4 flex items-center border border-slate-200 hover:bg-slate-50 text-sm text-slate-600 rounded-3xl transition">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden" style="box-shadow:0 1px 3px rgba(15,23,42,0.05)">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase text-[11px] tracking-wide font-semibold">
                        <th class="px-5 py-4 text-left w-10">No</th>
                        <th class="px-5 py-4 text-left">Document Title</th>
                        <th class="px-5 py-4 text-left w-36">Sender</th>
                        <th class="px-5 py-4 text-center w-28">Doc Type</th>
                        <th class="px-5 py-4 text-center w-24">File Type</th>
                        <th class="px-5 py-4 text-center w-24">File Size</th>
                        <th class="px-5 py-4 text-center w-24">Revisions</th>
                        <th class="px-5 py-4 text-left w-28">Approved At</th>
                        <th class="px-5 py-4 text-center w-28">Download</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($documents as $i => $doc)
                    <tr class="table-row">
                        <td class="px-5 py-4 text-slate-400 font-mono text-xs">
                            {{ $documents->firstItem() + $i }}
                        </td>

                        {{-- Title --}}
                        <td class="px-5 py-4">
                            <div class="font-medium text-slate-800 leading-snug">
                                {{ Str::limit($doc->title, 55) }}
                            </div>
                            @if($doc->document_date)
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    <i class="fa-regular fa-calendar mr-1"></i>{{ $doc->document_date->format('d M Y') }}
                                </div>
                            @endif
                        </td>

                        {{-- Sender --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-x-2">
                                <div class="w-7 h-7 bg-slate-200 rounded-full flex items-center justify-center text-[10px] font-bold text-slate-600 flex-shrink-0">
                                    {{ strtoupper(substr($doc->user->name, 0, 1)) }}
                                </div>
                                <span class="text-slate-700 text-xs font-medium truncate max-w-[100px]">{{ $doc->user->name }}</span>
                            </div>
                        </td>

                        {{-- Doc Type --}}
                        <td class="px-5 py-4 text-center">
                            <span class="px-2.5 py-1 text-[11px] font-medium bg-slate-100 text-slate-600 rounded-2xl whitespace-nowrap">
                                {{ $doc->document_type }}
                            </span>
                        </td>

                        {{-- File Extension --}}
                        <td class="px-5 py-4 text-center">
                            @php
                                $ext = $doc->fileExtension();
                                $extColor = match(strtolower($ext)) {
                                    'pdf'            => 'bg-red-100 text-red-700',
                                    'doc', 'docx'    => 'bg-blue-100 text-blue-700',
                                    'xls', 'xlsx'    => 'bg-emerald-100 text-emerald-700',
                                    'ppt', 'pptx'    => 'bg-orange-100 text-orange-700',
                                    'jpg', 'jpeg',
                                    'png', 'gif'     => 'bg-purple-100 text-purple-700',
                                    default          => 'bg-slate-100 text-slate-600',
                                };
                            @endphp
                            <span class="px-2.5 py-1 text-[11px] font-bold rounded-2xl {{ $extColor }}">
                                {{ $ext ?: '—' }}
                            </span>
                        </td>

                        {{-- File Size --}}
                        <td class="px-5 py-4 text-center text-xs text-slate-500 font-mono">
                            {{ $doc->fileSize() }}
                        </td>

                        {{-- Revisions --}}
                        <td class="px-5 py-4 text-center">
                            @if($doc->revision_count > 0)
                                <span class="inline-flex items-center gap-x-1 px-2.5 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full">
                                    <i class="fa-solid fa-rotate-left text-[9px]"></i>
                                    {{ $doc->revision_count }}×
                                </span>
                            @else
                                <span class="inline-flex items-center gap-x-1 px-2.5 py-1 bg-emerald-50 text-emerald-600 text-xs font-semibold rounded-full">
                                    <i class="fa-solid fa-check text-[9px]"></i>
                                    Direct
                                </span>
                            @endif
                        </td>

                        {{-- Approved At --}}
                        <td class="px-5 py-4 text-xs text-slate-500">
                            @if($doc->approved_at)
                                <div>{{ $doc->approved_at->format('d M Y') }}</div>
                                <div class="text-slate-400">{{ $doc->approved_at->format('H:i') }}</div>
                            @else
                                —
                            @endif
                        </td>

                        {{-- Download --}}
                        <td class="px-5 py-4 text-center">
                            <a href="{{ route('admin.documents.download', $doc) }}"
                               class="action-btn inline-flex items-center gap-x-1.5 px-3 py-1.5 text-xs bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-semibold transition">
                                <i class="fa-solid fa-download text-[10px]"></i>
                                Download
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-16 text-center">
                            <i class="fa-solid fa-circle-check text-4xl text-slate-200 mb-3 block"></i>
                            <p class="text-slate-400 text-sm">
                                {{ $search ? 'No approved documents match your search.' : 'No approved documents yet.' }}
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($documents->hasPages())
        <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 text-sm">
            <div class="text-slate-500 text-xs">
                Showing <span class="font-medium text-slate-700">{{ $documents->firstItem() }}–{{ $documents->lastItem() }}</span>
                of <span class="font-medium text-slate-700">{{ $documents->total() }}</span>
            </div>
            <div class="flex items-center gap-x-1">
                @if($documents->onFirstPage())
                    <span class="px-3 py-1.5 border border-slate-200 rounded-2xl text-xs text-slate-300 cursor-not-allowed">Previous</span>
                @else
                    <a href="{{ $documents->previousPageUrl() }}" class="px-3 py-1.5 border border-slate-200 rounded-2xl text-xs font-medium hover:bg-slate-50 transition">Previous</a>
                @endif
                @foreach($documents->getUrlRange(1, $documents->lastPage()) as $page => $url)
                    @if($page == $documents->currentPage())
                        <span class="px-3 py-1.5 bg-slate-900 text-white rounded-2xl text-xs font-medium">{{ $page }}</span>
                    @elseif(abs($page - $documents->currentPage()) <= 2)
                        <a href="{{ $url }}" class="px-3 py-1.5 border border-slate-200 rounded-2xl text-xs font-medium hover:bg-slate-50 transition">{{ $page }}</a>
                    @endif
                @endforeach
                @if($documents->hasMorePages())
                    <a href="{{ $documents->nextPageUrl() }}" class="px-3 py-1.5 border border-slate-200 rounded-2xl text-xs font-medium hover:bg-slate-50 transition">Next</a>
                @else
                    <span class="px-3 py-1.5 border border-slate-200 rounded-2xl text-xs text-slate-300 cursor-not-allowed">Next</span>
                @endif
            </div>
        </div>
        @endif
    </div>

</x-admin-layout>
