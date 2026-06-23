<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">My Documents</h2>
            <a href="{{ route('documents.create') }}"
               class="bg-red-600 hover:bg-red-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                + Submit Document
            </a>
        </div>
    </x-slot>

    {{-- Hidden single-delete form --}}
    <form id="single-delete-form" method="POST" style="display:none">
        @csrf
        @method('DELETE')
    </form>

    {{-- Hidden bulk-delete form --}}
    <form id="bulk-delete-form" method="POST" action="{{ route('documents.bulk-destroy') }}" style="display:none">
        @csrf
    </form>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm flex items-center gap-x-2">
                    <i class="fa-solid fa-check-circle text-emerald-500"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm flex items-center gap-x-2">
                    <i class="fa-solid fa-circle-exclamation text-red-400"></i> {{ session('error') }}
                </div>
            @endif

            {{-- Status Filter Tabs --}}
            @php
                $userId = auth()->id();
                $statusCounts = [
                    ''               => auth()->user()->documents()->count(),
                    'SUBMITTED'      => auth()->user()->documents()->where('status','SUBMITTED')->count(),
                    'UNDER_REVIEW'   => auth()->user()->documents()->where('status','UNDER_REVIEW')->count(),
                    'NEEDS_REVISION' => auth()->user()->documents()->where('status','NEEDS_REVISION')->count(),
                    'APPROVED'       => auth()->user()->documents()->where('status','APPROVED')->count(),
                    'REJECTED'       => auth()->user()->documents()->where('status','REJECTED')->count(),
                ];
                $tabs = [
                    ''               => ['label' => 'All',            'color' => 'bg-slate-800 text-white', 'inactive' => 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50'],
                    'SUBMITTED'      => ['label' => 'Submitted',      'color' => 'bg-blue-600 text-white',  'inactive' => 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50'],
                    'UNDER_REVIEW'   => ['label' => 'Under Review',   'color' => 'bg-amber-500 text-white', 'inactive' => 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50'],
                    'NEEDS_REVISION' => ['label' => 'Needs Revision', 'color' => 'bg-orange-500 text-white','inactive' => 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50'],
                    'APPROVED'       => ['label' => 'Approved',       'color' => 'bg-emerald-600 text-white','inactive' => 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50'],
                    'REJECTED'       => ['label' => 'Rejected',       'color' => 'bg-red-600 text-white',   'inactive' => 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50'],
                ];
            @endphp
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach($tabs as $val => $tab)
                    @php $isActive = ($status === $val) || ($val === '' && !$status); @endphp
                    <a href="{{ route('documents.index', array_filter(['status' => $val ?: null, 'per_page' => $perPage != 10 ? $perPage : null])) }}"
                       class="px-4 py-1.5 text-sm font-medium rounded-3xl flex items-center gap-x-2 transition {{ $isActive ? $tab['color'] : $tab['inactive'] }}">
                        {{ $tab['label'] }}
                        <span class="text-xs font-mono {{ $isActive ? 'opacity-80' : 'text-gray-400' }}">{{ $statusCounts[$val] }}</span>
                    </a>
                @endforeach
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

                {{-- TOOLBAR TOP --}}
                <div class="flex items-center gap-x-3 px-5 py-3 border-b border-gray-100 bg-gray-50">
                    <input type="checkbox" id="select-all-top"
                           class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500 cursor-pointer"
                           onchange="toggleAll(this.checked)"
                           title="Select all">
                    <select id="bulk-action-top" class="border border-gray-200 rounded-lg text-sm pl-3 pr-8 py-1.5 focus:outline-none focus:ring-2 focus:ring-red-500 bg-white">
                        <option value="">— Bulk Action —</option>
                        <option value="delete">Delete Selected</option>
                    </select>
                    <button type="button" onclick="applyBulkAction(document.getElementById('bulk-action-top').value)"
                            class="px-4 py-1.5 bg-slate-800 hover:bg-black text-white text-sm font-medium rounded-lg transition">
                        Apply
                    </button>
                    <span id="selected-count-top" class="text-xs text-gray-400 ml-1 hidden"><span class="selected-num font-semibold text-slate-700">0</span> selected</span>
                </div>

                @if($documents->isEmpty())
                    <div class="text-center py-16 text-gray-400">
                        <i class="fa-solid fa-folder-open text-4xl text-gray-200 mb-3 block"></i>
                        <p class="font-medium">No documents found</p>
                        <p class="text-sm mt-1">{{ $status ? 'Try a different status filter.' : 'Start by submitting your first document.' }}</p>
                        @if(!$status)
                        <a href="{{ route('documents.create') }}" class="mt-4 inline-block text-red-600 hover:underline text-sm">Submit Document</a>
                        @endif
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm" id="doc-table">
                            <thead class="bg-gray-50 text-gray-500 font-medium border-b border-gray-200 text-xs uppercase tracking-wide">
                                <tr>
                                    <th class="px-4 py-3 text-center w-10">
                                        <input type="checkbox" id="select-all-head"
                                               class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500 cursor-pointer"
                                               onchange="toggleAll(this.checked)">
                                    </th>
                                    <th class="px-4 py-3 text-left w-10">#</th>
                                    <th class="px-4 py-3 text-left">Title</th>
                                    <th class="px-4 py-3 text-left w-32">Type</th>
                                    <th class="px-4 py-3 text-left w-28">Doc Date</th>
                                    <th class="px-4 py-3 text-center w-32">Status</th>
                                    <th class="px-4 py-3 text-left w-24">Submitted</th>
                                    <th class="px-4 py-3 text-center w-24">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($documents as $i => $doc)
                                <tr class="hover:bg-gray-50 transition" data-id="{{ $doc->id }}">
                                    <td class="px-4 py-3 text-center">
                                        <input type="checkbox" class="row-check w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500 cursor-pointer"
                                               value="{{ $doc->id }}" onchange="onRowCheck()">
                                    </td>
                                    <td class="px-4 py-3 text-gray-400 font-mono text-xs">{{ $documents->firstItem() + $i }}</td>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-800 leading-snug">{{ Str::limit($doc->title, 55) }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-lg">{{ $doc->document_type }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500 text-xs">{{ $doc->document_date->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $doc->statusBadge() }}">
                                            {{ $doc->statusLabel() }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-400 text-xs">{{ $doc->created_at->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-x-2">
                                            <a href="{{ route('documents.show', $doc) }}"
                                               class="inline-flex items-center gap-x-1 px-2.5 py-1 text-xs border border-gray-200 hover:bg-gray-50 rounded-lg font-medium text-gray-700 transition">
                                                <i class="fa-solid fa-eye text-[10px]"></i> View
                                            </a>
                                            <button type="button"
                                                    onclick="confirmDeleteSingle('{{ route('documents.destroy', $doc) }}', '{{ addslashes($doc->title) }}')"
                                                    class="inline-flex items-center justify-center w-7 h-7 text-red-500 hover:text-white hover:bg-red-500 border border-red-200 hover:border-red-500 rounded-lg transition"
                                                    title="Delete">
                                                <i class="fa-solid fa-xmark text-xs"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- TOOLBAR BOTTOM --}}
                    <div class="flex flex-wrap items-center justify-between gap-y-3 px-5 py-3 border-t border-gray-100 bg-gray-50">
                        {{-- Left: bulk + per page --}}
                        <div class="flex items-center gap-x-3 flex-wrap gap-y-2">
                            <input type="checkbox" id="select-all-bot"
                                   class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500 cursor-pointer"
                                   onchange="toggleAll(this.checked)"
                                   title="Select all">
                            <select id="bulk-action-bot" class="border border-gray-200 rounded-lg text-sm pl-3 pr-8 py-1.5 focus:outline-none focus:ring-2 focus:ring-red-500 bg-white">
                                <option value="">— Bulk Action —</option>
                                <option value="delete">Delete Selected</option>
                            </select>
                            <button type="button" onclick="applyBulkAction(document.getElementById('bulk-action-bot').value)"
                                    class="px-4 py-1.5 bg-slate-800 hover:bg-black text-white text-sm font-medium rounded-lg transition">
                                Apply
                            </button>
                            <span id="selected-count-bot" class="text-xs text-gray-400 hidden"><span class="selected-num font-semibold text-slate-700">0</span> selected</span>

                            {{-- Per page --}}
                            <span class="text-xs text-gray-500 ml-3">Items per page:</span>
                            <form id="per-page-form" method="GET" action="{{ route('documents.index') }}" class="inline-flex">
                                @if($status)
                                    <input type="hidden" name="status" value="{{ $status }}">
                                @endif
                                <input type="hidden" name="page" value="1">
                                <select name="per_page" onchange="document.getElementById('per-page-form').submit()"
                                        class="border border-gray-200 rounded-lg text-sm pl-2 pr-7 py-1.5 focus:outline-none focus:ring-2 focus:ring-red-500 bg-white">
                                    @foreach([5, 10, 20, 50] as $n)
                                        <option value="{{ $n }}" {{ $perPage == $n ? 'selected' : '' }}>{{ $n }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>

                        {{-- Right: pagination --}}
                        @if($documents->hasPages())
                        <div class="flex items-center gap-x-1 text-sm">
                            <span class="text-xs text-gray-400 mr-2">
                                {{ $documents->firstItem() }}–{{ $documents->lastItem() }} / {{ $documents->total() }}
                            </span>
                            @if($documents->onFirstPage())
                                <span class="px-2.5 py-1 border border-gray-200 rounded-lg text-xs text-gray-300 cursor-not-allowed">‹</span>
                            @else
                                <a href="{{ $documents->previousPageUrl() }}" class="px-2.5 py-1 border border-gray-200 rounded-lg text-xs font-medium hover:bg-white transition">‹</a>
                            @endif
                            @foreach($documents->getUrlRange(1, $documents->lastPage()) as $page => $url)
                                @if($page == $documents->currentPage())
                                    <span class="px-2.5 py-1 bg-slate-800 text-white rounded-lg text-xs font-medium">{{ $page }}</span>
                                @elseif(abs($page - $documents->currentPage()) <= 2)
                                    <a href="{{ $url }}" class="px-2.5 py-1 border border-gray-200 rounded-lg text-xs font-medium hover:bg-white transition">{{ $page }}</a>
                                @endif
                            @endforeach
                            @if($documents->hasMorePages())
                                <a href="{{ $documents->nextPageUrl() }}" class="px-2.5 py-1 border border-gray-200 rounded-lg text-xs font-medium hover:bg-white transition">›</a>
                            @else
                                <span class="px-2.5 py-1 border border-gray-200 rounded-lg text-xs text-gray-300 cursor-not-allowed">›</span>
                            @endif
                        </div>
                        @endif
                    </div>
                @endif

            </div>{{-- /card --}}
        </div>
    </div>

<script>
function toggleAll(checked) {
    document.querySelectorAll('.row-check').forEach(cb => cb.checked = checked);
    document.getElementById('select-all-top').checked  = checked;
    document.getElementById('select-all-head').checked = checked;
    document.getElementById('select-all-bot').checked  = checked;
    updateSelectedCount();
}

function onRowCheck() {
    const all     = document.querySelectorAll('.row-check');
    const checked = document.querySelectorAll('.row-check:checked');
    const allChecked = all.length === checked.length;
    document.getElementById('select-all-top').checked  = allChecked;
    document.getElementById('select-all-head').checked = allChecked;
    document.getElementById('select-all-bot').checked  = allChecked;
    updateSelectedCount();
}

function updateSelectedCount() {
    const n = document.querySelectorAll('.row-check:checked').length;
    document.querySelectorAll('.selected-num').forEach(el => el.textContent = n);
    ['selected-count-top', 'selected-count-bot'].forEach(id => {
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
</x-app-layout>
