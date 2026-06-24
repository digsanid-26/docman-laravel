<x-admin-layout title="Dashboard">

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
        @foreach([
            ['label' => 'Total',          'value' => $stats['total'],          'color' => 'border-slate-300',  'text' => 'text-slate-800',   'bg' => 'bg-white'],
            ['label' => 'Submitted',      'value' => $stats['submitted'],      'color' => 'border-blue-300',   'text' => 'text-blue-700',    'bg' => 'bg-blue-50'],
            ['label' => 'Under Review',   'value' => $stats['under_review'],   'color' => 'border-amber-300',  'text' => 'text-amber-700',   'bg' => 'bg-amber-50'],
            ['label' => 'Needs Revision', 'value' => $stats['needs_revision'], 'color' => 'border-orange-300', 'text' => 'text-orange-700',  'bg' => 'bg-orange-50'],
            ['label' => 'Approved',       'value' => $stats['approved'],       'color' => 'border-emerald-300','text' => 'text-emerald-700', 'bg' => 'bg-emerald-50'],
            ['label' => 'Rejected',       'value' => $stats['rejected'],       'color' => 'border-red-300',    'text' => 'text-red-700',     'bg' => 'bg-red-50'],
        ] as $card)
        <div class="{{ $card['bg'] }} border-l-4 {{ $card['color'] }} rounded-2xl p-4">
            <div class="text-3xl font-bold {{ $card['text'] }} tracking-tight">{{ $card['value'] }}</div>
            <div class="text-xs text-slate-500 mt-1 font-medium">{{ $card['label'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- Chart + Recent --}}
    <div class="grid lg:grid-cols-3 gap-5 mb-5">

        {{-- Pie Chart --}}
        <div class="bg-white border border-slate-100 rounded-3xl p-6 flex flex-col items-center" style="box-shadow:0 1px 3px rgba(15,23,42,0.05)">
            <h3 class="font-semibold text-slate-900 text-sm self-start mb-4 w-full">
                <i class="fa-solid fa-chart-pie text-red-400 mr-1.5"></i> Document Status
            </h3>

            <div style="max-width:220px;width:100%">
                <canvas id="statusChart"></canvas>
            </div>

            {{-- Custom two-column left-aligned legend --}}
            @php
                $legendItems = [
                    ['Submitted',      $stats['submitted'],      '#3B82F6'],
                    ['Under Review',   $stats['under_review'],   '#F59E0B'],
                    ['Needs Revision', $stats['needs_revision'], '#F97316'],
                    ['Approved',       $stats['approved'],       '#10B981'],
                    ['Rejected',       $stats['rejected'],       '#EF4444'],
                ];
            @endphp
            <div class="w-full grid grid-cols-2 gap-x-3 gap-y-2 mt-5 text-sm text-slate-600">
                @foreach($legendItems as [$label, $value, $color])
                    <div class="flex items-center gap-x-2">
                        <span class="w-3 h-3 rounded-full flex-shrink-0" style="background-color:{{ $color }}"></span>
                        <span class="truncate">{{ $label }}</span>
                        <span class="text-slate-400 text-xs font-mono ml-auto">{{ $value }}</span>
                    </div>
                @endforeach
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    new Chart(document.getElementById('statusChart'), {
                        type: 'pie',
                        data: {
                            labels: ['Submitted', 'Under Review', 'Needs Revision', 'Approved', 'Rejected'],
                            datasets: [{
                                data: [
                                    {{ $stats['submitted'] }},
                                    {{ $stats['under_review'] }},
                                    {{ $stats['needs_revision'] }},
                                    {{ $stats['approved'] }},
                                    {{ $stats['rejected'] }}
                                ],
                                backgroundColor: ['#3B82F6','#F59E0B','#F97316','#10B981','#EF4444'],
                                borderWidth: 2,
                                borderColor: '#fff',
                            }]
                        },
                        options: {
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return context.label + ': ' + context.parsed;
                                        }
                                    }
                                }
                            }
                        }
                    });
                });
            </script>
        </div>

        {{-- Quick Stats --}}
        <div class="lg:col-span-2 bg-white border border-slate-100 rounded-3xl p-6" style="box-shadow:0 1px 3px rgba(15,23,42,0.05)">
            <h3 class="font-semibold text-slate-900 text-sm mb-4">
                <i class="fa-solid fa-list-check text-red-400 mr-1.5"></i> Pending Actions
            </h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-2xl">
                    <div class="flex items-center gap-x-2 text-sm">
                        <i class="fa-solid fa-paper-plane text-blue-500"></i>
                        <span class="text-blue-800 font-medium">Awaiting review</span>
                    </div>
                    <a href="{{ route('admin.documents.index', ['status' => 'SUBMITTED']) }}"
                       class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                        {{ $stats['submitted'] }} documents →
                    </a>
                </div>
                <div class="flex items-center justify-between p-3 bg-amber-50 rounded-2xl">
                    <div class="flex items-center gap-x-2 text-sm">
                        <i class="fa-solid fa-eye text-amber-500"></i>
                        <span class="text-amber-800 font-medium">Currently under review</span>
                    </div>
                    <a href="{{ route('admin.documents.index', ['status' => 'UNDER_REVIEW']) }}"
                       class="text-xs font-semibold text-amber-600 hover:text-amber-700">
                        {{ $stats['under_review'] }} documents →
                    </a>
                </div>
                <div class="flex items-center justify-between p-3 bg-orange-50 rounded-2xl">
                    <div class="flex items-center gap-x-2 text-sm">
                        <i class="fa-solid fa-exclamation-triangle text-orange-500"></i>
                        <span class="text-orange-800 font-medium">Waiting for revision</span>
                    </div>
                    <a href="{{ route('admin.documents.index', ['status' => 'NEEDS_REVISION']) }}"
                       class="text-xs font-semibold text-orange-600 hover:text-orange-700">
                        {{ $stats['needs_revision'] }} documents →
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Documents Table --}}
    <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden" style="box-shadow:0 1px 3px rgba(15,23,42,0.05)">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <div>
                <h2 class="font-semibold text-slate-900">Latest 10 Documents</h2>
                <p class="text-xs text-slate-400 mt-0.5">Most recently submitted documents</p>
            </div>
            <a href="{{ route('admin.documents.index') }}"
               class="flex items-center gap-x-1.5 text-xs font-semibold text-red-600 hover:text-red-700 transition">
                View All <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase text-[11px] tracking-wide font-semibold">
                        <th class="px-6 py-3.5 text-left">Title</th>
                        <th class="px-6 py-3.5 text-left">Submitted By</th>
                        <th class="px-6 py-3.5 text-center">Status</th>
                        <th class="px-6 py-3.5 text-left">Date</th>
                        <th class="px-6 py-3.5 text-center">Action</th>
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
                                    'SUBMITTED'      => ['bg-blue-100 text-blue-700',     'fa-paper-plane'],
                                    'UNDER_REVIEW'   => ['bg-amber-100 text-amber-700',   'fa-eye'],
                                    'NEEDS_REVISION' => ['bg-orange-100 text-orange-700', 'fa-exclamation-triangle'],
                                    'APPROVED'       => ['bg-emerald-100 text-emerald-700','fa-check-circle'],
                                    'REJECTED'       => ['bg-red-100 text-red-700',       'fa-times-circle'],
                                ];
                                [$cls, $ico] = $badgeMap[$doc->status] ?? ['bg-slate-100 text-slate-600','fa-circle'];
                            @endphp
                            <span class="inline-flex items-center gap-x-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $cls }}">
                                <i class="fa-solid {{ $ico }} text-[10px]"></i> {{ $doc->statusLabel() }}
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
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-sm">No documents yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-admin-layout>
