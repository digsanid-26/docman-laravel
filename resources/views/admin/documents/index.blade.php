<x-admin-layout title="All Documents">

    {{-- Hidden single-delete form --}}
    <form id="single-delete-form" method="POST" style="display:none">
        @csrf
        @method('DELETE')
    </form>

    {{-- Hidden bulk-delete form --}}
    <form id="bulk-delete-form" method="POST" action="{{ route('admin.documents.bulk-destroy') }}" style="display:none">
        @csrf
    </form>

    @if(session('success'))
        <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-sm flex items-center gap-x-2">
            <i class="fa-solid fa-check-circle text-emerald-500"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl text-sm flex items-center gap-x-2">
            <i class="fa-solid fa-circle-exclamation text-red-400"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Header Actions --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-y-4 mb-5">
        <div>
            <h2 class="font-semibold text-xl text-slate-900">All Documents</h2>
            <p class="text-xs text-slate-500 mt-0.5">{{ $documents->total() }} document(s) found{{ $status ? ' — filter: ' . $status : '' }}</p>
        </div>
        <a href="{{ route('admin.documents.export', $status ? ['status' => $status] : []) }}"
           class="flex items-center gap-x-2 px-5 h-10 bg-white border border-slate-200 hover:bg-slate-50 transition text-sm font-semibold rounded-3xl text-slate-700">
            <i class="fa-solid fa-file-excel text-emerald-600"></i>
            <span>Export Excel</span>
        </a>
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
            <a href="{{ route('admin.documents.index', array_filter(['status' => $val ?: null, 'per_page' => $perPage != 15 ? $perPage : null])) }}"
               class="filter-tab {{ $isActive ? 'active' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }} px-5 py-2 text-sm font-medium rounded-3xl flex items-center gap-x-2 transition">
                <span>{{ $tab['label'] }}</span>
                <span class="px-2 py-0.5 text-xs rounded-full font-mono {{ $isActive ? 'bg-white/20 text-white' : $tab['badge'] }}">
                    {{ $statusCounts[$val] }}
                </span>
            </a>
        @endforeach
    </div>

    {{-- Table Card --}}
    <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden" style="box-shadow:0 1px 3px rgba(15,23,42,0.05)">

        {{-- TOOLBAR TOP --}}
        <div class="flex items-center gap-x-3 px-5 py-3 border-b border-slate-100 bg-slate-50">
            <input type="checkbox" id="select-all-top"
                   class="w-4 h-4 rounded border-slate-300 text-red-600 focus:ring-red-500 cursor-pointer"
                   onchange="toggleAll(this.checked)" title="Select all">
            <select id="bulk-action-top" class="border border-slate-200 rounded-xl text-sm px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-red-500 bg-white text-slate-700">
                <option value="">— Bulk Action —</option>
                <option value="delete">Delete Selected</option>
            </select>
            <button type="button" onclick="applyBulkAction(document.getElementById('bulk-action-top').value)"
                    class="px-4 py-1.5 bg-slate-800 hover:bg-black text-white text-sm font-semibold rounded-xl transition">
                Apply
            </button>
            <span id="selected-count-top" class="text-xs text-slate-400 hidden">
                <span class="selected-num font-semibold text-slate-700">0</span> selected
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase text-[11px] tracking-wide font-semibold">
                        <th class="px-4 py-4 text-center w-10">
                            <input type="checkbox" id="select-all-head"
                                   class="w-4 h-4 rounded border-slate-300 text-red-600 focus:ring-red-500 cursor-pointer"
                                   onchange="toggleAll(this.checked)">
                        </th>
                        <th class="px-4 py-4 text-left w-10">No</th>
                        <th class="px-4 py-4 text-left">Document Title</th>
                        <th class="px-4 py-4 text-left w-32">Type</th>
                        <th class="px-4 py-4 text-left w-36">Submitted By</th>
                        <th class="px-4 py-4 text-center w-32">Status</th>
                        <th class="px-4 py-4 text-left w-24">Date</th>
                        <th class="px-4 py-4 text-center w-36">Action</th>
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
                        <td class="px-4 py-4 text-center">
                            <input type="checkbox" class="row-check w-4 h-4 rounded border-slate-300 text-red-600 focus:ring-red-500 cursor-pointer"
                                   value="{{ $doc->id }}" onchange="onRowCheck()">
                        </td>
                        <td class="px-4 py-4 text-slate-400 font-mono text-xs">{{ $documents->firstItem() + $i }}</td>
                        <td class="px-4 py-4">
                            <div class="font-medium text-slate-800">{{ Str::limit($doc->title, 50) }}</div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-2.5 py-1 text-xs font-medium bg-slate-100 text-slate-600 rounded-2xl">{{ $doc->document_type }}</span>
                        </td>
                        <td class="px-4 py-4 text-slate-600 text-xs">{{ $doc->user->name }}</td>
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center gap-x-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeMap[$doc->status] ?? 'bg-slate-100 text-slate-600' }}">
                                <i class="fa-solid {{ $iconMap[$doc->status] ?? 'fa-circle' }} text-[10px]"></i>
                                {{ $doc->statusLabel() }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-xs text-slate-500">{{ $doc->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-4">
                            <div class="flex justify-center items-center gap-x-1.5">
                                <a href="{{ route('admin.documents.show', $doc) }}"
                                   class="action-btn inline-flex items-center gap-x-1.5 px-3 py-1.5 text-xs bg-white border border-slate-200 hover:bg-slate-50 rounded-2xl font-medium text-slate-700">
                                    <i class="fa-solid fa-eye text-[10px]"></i> Review
                                </a>
                                <button type="button"
                                        onclick="confirmDeleteSingle('{{ route('admin.documents.destroy', $doc) }}', '{{ addslashes($doc->title) }}')"
                                        class="action-btn inline-flex items-center justify-center w-7 h-7 text-red-500 hover:text-white hover:bg-red-500 border border-red-200 hover:border-red-500 rounded-xl transition"
                                        title="Delete">
                                    <i class="fa-solid fa-xmark text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-14 text-center text-slate-400 text-sm">
                            <i class="fa-solid fa-folder-open text-3xl text-slate-200 mb-3 block"></i>
                            No documents found{{ $status ? ' with this status' : '' }}.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- TOOLBAR BOTTOM --}}
        <div class="flex flex-wrap items-center justify-between gap-y-3 px-5 py-3 border-t border-slate-100 bg-slate-50">
            {{-- Left: bulk + per page --}}
            <div class="flex items-center gap-x-3 flex-wrap gap-y-2">
                <input type="checkbox" id="select-all-bot"
                       class="w-4 h-4 rounded border-slate-300 text-red-600 focus:ring-red-500 cursor-pointer"
                       onchange="toggleAll(this.checked)" title="Select all">
                <select id="bulk-action-bot" class="border border-slate-200 rounded-xl text-sm px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-red-500 bg-white text-slate-700">
                    <option value="">— Bulk Action —</option>
                    <option value="delete">Delete Selected</option>
                </select>
                <button type="button" onclick="applyBulkAction(document.getElementById('bulk-action-bot').value)"
                        class="px-4 py-1.5 bg-slate-800 hover:bg-black text-white text-sm font-semibold rounded-xl transition">
                    Apply
                </button>
                <span id="selected-count-bot" class="text-xs text-slate-400 hidden">
                    <span class="selected-num font-semibold text-slate-700">0</span> selected
                </span>

                <span class="text-xs text-slate-500 ml-3">Items per page:</span>
                <form id="per-page-form" method="GET" action="{{ route('admin.documents.index') }}" class="inline-flex">
                    @if($status)
                        <input type="hidden" name="status" value="{{ $status }}">
                    @endif
                    <input type="hidden" name="page" value="1">
                    <select name="per_page" onchange="document.getElementById('per-page-form').submit()"
                            class="border border-slate-200 rounded-xl text-sm px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-red-500 bg-white">
                        @foreach([5, 10, 20, 50] as $n)
                            <option value="{{ $n }}" {{ $perPage == $n ? 'selected' : '' }}>{{ $n }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            {{-- Right: pagination --}}
            @if($documents->hasPages())
            <div class="flex items-center gap-x-1">
                <span class="text-xs text-slate-400 mr-2">
                    {{ $documents->firstItem() }}–{{ $documents->lastItem() }} / {{ $documents->total() }}
                </span>
                @if($documents->onFirstPage())
                    <span class="px-2.5 py-1.5 border border-slate-200 rounded-2xl text-xs text-slate-300 cursor-not-allowed">‹</span>
                @else
                    <a href="{{ $documents->previousPageUrl() }}" class="px-2.5 py-1.5 border border-slate-200 rounded-2xl text-xs font-medium hover:bg-white transition">‹</a>
                @endif
                @foreach($documents->getUrlRange(1, $documents->lastPage()) as $page => $url)
                    @if($page == $documents->currentPage())
                        <span class="px-2.5 py-1.5 bg-slate-900 text-white rounded-2xl text-xs font-medium">{{ $page }}</span>
                    @elseif(abs($page - $documents->currentPage()) <= 2)
                        <a href="{{ $url }}" class="px-2.5 py-1.5 border border-slate-200 rounded-2xl text-xs font-medium hover:bg-white transition">{{ $page }}</a>
                    @endif
                @endforeach
                @if($documents->hasMorePages())
                    <a href="{{ $documents->nextPageUrl() }}" class="px-2.5 py-1.5 border border-slate-200 rounded-2xl text-xs font-medium hover:bg-white transition">›</a>
                @else
                    <span class="px-2.5 py-1.5 border border-slate-200 rounded-2xl text-xs text-slate-300 cursor-not-allowed">›</span>
                @endif
            </div>
            @endif
        </div>

    </div>{{-- /card --}}

<script>
function toggleAll(checked) {
    document.querySelectorAll('.row-check').forEach(cb => cb.checked = checked);
    ['select-all-top','select-all-head','select-all-bot'].forEach(id => {
        document.getElementById(id).checked = checked;
    });
    updateSelectedCount();
}

function onRowCheck() {
    const all     = document.querySelectorAll('.row-check');
    const checked = document.querySelectorAll('.row-check:checked');
    const allChecked = all.length === checked.length;
    ['select-all-top','select-all-head','select-all-bot'].forEach(id => {
        document.getElementById(id).checked = allChecked;
    });
    updateSelectedCount();
}

function updateSelectedCount() {
    const n = document.querySelectorAll('.row-check:checked').length;
    document.querySelectorAll('.selected-num').forEach(el => el.textContent = n);
    ['selected-count-top','selected-count-bot'].forEach(id => {
        document.getElementById(id).classList.toggle('hidden', n === 0);
    });
}

function confirmDeleteSingle(url, title) {
    if (!confirm('Delete "' + title + '"?\nThis action cannot be undone.')) return;
    const form = document.getElementById('single-delete-form');
    form.action = url;
    form.submit();
}

function applyBulkAction(action) {
    if (!action) { alert('Please select a bulk action first.'); return; }
    const checked = document.querySelectorAll('.row-check:checked');
    if (checked.length === 0) { alert('Please select at least one document.'); return; }
    if (action === 'delete') {
        if (!confirm('Delete ' + checked.length + ' selected document(s)?\nThis action cannot be undone.')) return;
        const form = document.getElementById('bulk-delete-form');
        form.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());
        checked.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden'; input.name = 'ids[]'; input.value = cb.value;
            form.appendChild(input);
        });
        form.submit();
    }
}
</script>

</x-admin-layout>
