<x-admin-layout title="All Documents">

    @if(session('success'))
        <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-sm flex items-center gap-x-2">
            <i class="fa-solid fa-check-circle text-emerald-500"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Header Actions --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-y-4 mb-5">
        <div>
            <h2 class="font-semibold text-xl text-slate-900">All Documents</h2>
            <p class="text-xs text-slate-500 mt-0.5">{{ $documents->total() }} document(s) found{{ $status ? ' — filter: ' . $status : '' }}</p>
        </div>
        <div class="flex items-center gap-x-3">
            <a href="{{ route('admin.documents.export', $status ? ['status' => $status] : []) }}"
               class="flex items-center gap-x-2 px-5 h-10 bg-white border border-slate-200 hover:bg-slate-50 transition text-sm font-semibold rounded-3xl text-slate-700">
                <i class="fa-solid fa-file-excel text-emerald-600"></i>
                <span>Export Excel</span>
            </a>
        </div>
    </div>

    {{-- Filter Tabs --}}
    @php
        $statusCounts = [
            ''               => \App\Models\Document::count(),
            'SUBMITTED'      => \App\Models\Document::where('status','SUBMITTED')->count(),
            'UNDER_REVIEW'   => \App\Models\Document::where('status','UNDER_REVIEW')->count(),
            'NEEDS_REVISION' => \App\Models\Document::where('status','NEEDS_REVISION')->count(),
            'APPROVED'       => \App\Models\Document::where('status','APPROVED')->count(),
            'REJECTED'       => \App\Models\Document::where('status','REJECTED')->count(),
        ];
        $tabs = [
            ''               => ['label' => 'All',            'badge' => 'bg-slate-200 text-slate-600'],
            'SUBMITTED'      => ['label' => 'Submitted',      'badge' => 'bg-blue-100 text-blue-600'],
            'UNDER_REVIEW'   => ['label' => 'Under Review',   'badge' => 'bg-amber-100 text-amber-600'],
            'NEEDS_REVISION' => ['label' => 'Needs Revision', 'badge' => 'bg-orange-100 text-orange-600'],
            'APPROVED'       => ['label' => 'Approved',       'badge' => 'bg-emerald-100 text-emerald-600'],
            'REJECTED'       => ['label' => 'Rejected',       'badge' => 'bg-red-100 text-red-600'],
        ];
    @endphp
    <div class="flex flex-wrap gap-2 mb-5">
        @foreach($tabs as $val => $tab)
            @php $isActive = ($status === $val) || ($val === '' && !$status); @endphp
            <a href="{{ route('admin.documents.index', $val ? ['status' => $val] : []) }}"
               class="filter-tab {{ $isActive ? 'active' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }} px-5 py-2 text-sm font-medium rounded-3xl flex items-center gap-x-2 transition">
                <span>{{ $tab['label'] }}</span>
                <span class="px-2 py-0.5 text-xs rounded-full font-mono {{ $isActive ? 'bg-white/20 text-white' : $tab['badge'] }}">
                    {{ $statusCounts[$val] }}
                </span>
            </a>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden" style="box-shadow:0 1px 3px rgba(15,23,42,0.05)">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase text-[11px] tracking-wide font-semibold">
                        <th class="px-6 py-4 text-left w-12">No</th>
                        <th class="px-6 py-4 text-left">Document Title</th>
                        <th class="px-6 py-4 text-left w-32">Type</th>
                        <th class="px-6 py-4 text-left w-40">Submitted By</th>
                        <th class="px-6 py-4 text-center w-32">Status</th>
                        <th class="px-6 py-4 text-left w-28">Date</th>
                        <th class="px-6 py-4 text-center w-36">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($documents as $i => $doc)
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
                    <tr class="table-row">
                        <td class="px-6 py-4 text-slate-400 font-mono text-xs">{{ $documents->firstItem() + $i }}</td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-800">{{ Str::limit($doc->title, 50) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-medium bg-slate-100 text-slate-600 rounded-2xl">{{ $doc->document_type }}</span>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $doc->user->name }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-x-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeMap[$doc->status] ?? 'bg-slate-100 text-slate-600' }}">
                                <i class="fa-solid {{ $iconMap[$doc->status] ?? 'fa-circle' }} text-[10px]"></i>
                                {{ $doc->statusLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500">{{ $doc->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-x-2">
                                <a href="{{ route('admin.documents.show', $doc) }}"
                                   class="action-btn inline-flex items-center gap-x-1.5 px-3 py-1.5 text-xs bg-white border border-slate-200 hover:bg-slate-50 rounded-2xl font-medium text-slate-700">
                                    <i class="fa-solid fa-eye text-[10px]"></i> <span>Review</span>
                                </a>
                                @if(!$doc->isFinal())
                                    <a href="{{ route('admin.documents.show', $doc) }}"
                                       class="action-btn inline-flex items-center gap-x-1.5 px-3 py-1.5 text-xs bg-slate-900 hover:bg-black text-white rounded-2xl font-medium">
                                        <i class="fa-solid fa-pen text-[10px]"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-14 text-center text-slate-400 text-sm">
                            <i class="fa-solid fa-folder-open text-3xl text-slate-200 mb-3 block"></i>
                            No documents found{{ $status ? ' with this status' : '' }}.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($documents->hasPages())
        <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 text-sm">
            <div class="text-slate-500 text-xs">
                Showing <span class="font-medium text-slate-700">{{ $documents->firstItem() }}-{{ $documents->lastItem() }}</span>
                of <span class="font-medium text-slate-700">{{ $documents->total() }}</span>
            </div>
            <div class="flex items-center gap-x-1">
                @if($documents->onFirstPage())
                    <span class="px-3 py-1.5 border border-slate-200 rounded-2xl text-xs text-slate-300 cursor-not-allowed">Previous</span>
                @else
                    <a href="{{ $documents->previousPageUrl() }}" class="px-3 py-1.5 border border-slate-200 rounded-2xl text-xs font-medium hover:bg-white transition">Previous</a>
                @endif
                @foreach($documents->getUrlRange(1, $documents->lastPage()) as $page => $url)
                    @if($page == $documents->currentPage())
                        <span class="px-3 py-1.5 bg-slate-900 text-white rounded-2xl text-xs font-medium">{{ $page }}</span>
                    @elseif(abs($page - $documents->currentPage()) <= 2)
                        <a href="{{ $url }}" class="px-3 py-1.5 border border-slate-200 rounded-2xl text-xs font-medium hover:bg-white transition">{{ $page }}</a>
                    @endif
                @endforeach
                @if($documents->hasMorePages())
                    <a href="{{ $documents->nextPageUrl() }}" class="px-3 py-1.5 border border-slate-200 rounded-2xl text-xs font-medium hover:bg-white transition">Next</a>
                @else
                    <span class="px-3 py-1.5 border border-slate-200 rounded-2xl text-xs text-slate-300 cursor-not-allowed">Next</span>
                @endif
            </div>
        </div>
        @endif
    </div>

</x-admin-layout>
