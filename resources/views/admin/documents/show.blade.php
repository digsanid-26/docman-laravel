<x-admin-layout title="Review Document">

    {{-- Back + breadcrumb --}}
    <div class="flex items-center gap-x-2 text-sm text-slate-500 mb-5">
        <a href="{{ route('admin.documents.index') }}" class="hover:text-slate-800 transition flex items-center gap-x-1.5">
            <i class="fa-solid fa-arrow-left text-xs"></i> Document List
        </a>
        <span class="text-slate-300">/</span>
        <span class="text-slate-800 font-medium truncate max-w-xs">{{ Str::limit($document->title, 40) }}</span>
    </div>

    <div class="grid lg:grid-cols-3 gap-5">

        {{-- LEFT: Document Info + Decision Panel --}}
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
                        <p class="text-xs text-slate-400 font-medium uppercase tracking-wide mb-1">Document Type</p>
                        <p class="font-medium text-slate-800">{{ $document->document_type }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase tracking-wide mb-1">Document Date</p>
                        <p class="font-medium text-slate-800">{{ $document->document_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase tracking-wide mb-1">Submitted</p>
                        <p class="font-medium text-slate-800">{{ $document->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @if($document->approved_at)
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase tracking-wide mb-1">Approved At</p>
                        <p class="font-medium text-emerald-700">{{ $document->approved_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                </div>

                <div>
                    <p class="text-xs text-slate-400 font-medium uppercase tracking-wide mb-2">Description</p>
                    <p class="text-slate-700 bg-slate-50 rounded-2xl px-4 py-3 text-sm leading-relaxed">{{ $document->description }}</p>
                </div>

                <div class="flex gap-x-3">
                    <a href="{{ route('admin.documents.download', $document) }}"
                       class="action-btn inline-flex items-center gap-x-2 px-4 h-10 bg-white border border-slate-200 hover:bg-slate-50 text-sm font-semibold rounded-3xl text-slate-700 transition">
                        <i class="fa-solid fa-download text-slate-400"></i> Download File
                    </a>
                </div>
            </div>

            {{-- Decision Panel (moved from right sidebar) --}}
            @if(!$document->isFinal())
            <div class="bg-white border border-slate-100 rounded-3xl p-6" style="box-shadow:0 1px 3px rgba(15,23,42,0.05)">
                <h4 class="font-semibold text-slate-900 mb-5 flex items-center gap-x-2">
                    <i class="fa-solid fa-gavel text-slate-400 text-sm"></i> Give Decision
                </h4>

                <form method="POST" action="{{ route('admin.documents.review', $document) }}"
                      x-data="{ action: '{{ old('action', '') }}' }"
                      x-on:submit.prevent="
                        if(!action){ alert('Please select an action first.'); return; }
                        if(confirm('Are you sure you want to proceed?')) {
                            $refs.notesInput.value = document.querySelector('#quill-editor .ql-editor').innerHTML;
                            $el.submit();
                        }
                      ">
                    @csrf
                    <input type="hidden" name="notes" x-ref="notesInput" value="{{ old('notes') }}">

                    <div class="space-y-5">
                        {{-- Action selector --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">
                                Action <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-3 gap-3">

                                {{-- Needs Revision --}}
                                <label @click="action = 'needs_revision'"
                                       :class="action === 'needs_revision'
                                           ? 'border-orange-500 bg-orange-50 ring-1 ring-orange-400'
                                           : 'border-slate-200 hover:border-orange-300 bg-white'"
                                       class="flex flex-col items-center gap-y-2 p-3 border-2 rounded-2xl cursor-pointer transition">
                                    <input type="radio" name="action" value="needs_revision" x-model="action" class="hidden">
                                    <i class="fa-solid fa-exclamation-triangle text-lg transition"
                                       :class="action === 'needs_revision' ? 'text-orange-500' : 'text-slate-300'"></i>
                                    <span class="text-xs font-semibold text-center transition"
                                          :class="action === 'needs_revision' ? 'text-orange-700' : 'text-slate-600'">Needs Revision</span>
                                </label>

                                {{-- Approve --}}
                                <label @click="action = 'approved'"
                                       :class="action === 'approved'
                                           ? 'border-emerald-500 bg-emerald-50 ring-1 ring-emerald-400'
                                           : 'border-slate-200 hover:border-emerald-300 bg-white'"
                                       class="flex flex-col items-center gap-y-2 p-3 border-2 rounded-2xl cursor-pointer transition">
                                    <input type="radio" name="action" value="approved" x-model="action" class="hidden">
                                    <i class="fa-solid fa-check-circle text-lg transition"
                                       :class="action === 'approved' ? 'text-emerald-500' : 'text-slate-300'"></i>
                                    <span class="text-xs font-semibold text-center transition"
                                          :class="action === 'approved' ? 'text-emerald-700' : 'text-slate-600'">Approve</span>
                                </label>

                                {{-- Reject --}}
                                <label @click="action = 'rejected'"
                                       :class="action === 'rejected'
                                           ? 'border-red-500 bg-red-50 ring-1 ring-red-400'
                                           : 'border-slate-200 hover:border-red-300 bg-white'"
                                       class="flex flex-col items-center gap-y-2 p-3 border-2 rounded-2xl cursor-pointer transition">
                                    <input type="radio" name="action" value="rejected" x-model="action" class="hidden">
                                    <i class="fa-solid fa-times-circle text-lg transition"
                                       :class="action === 'rejected' ? 'text-red-500' : 'text-slate-300'"></i>
                                    <span class="text-xs font-semibold text-center transition"
                                          :class="action === 'rejected' ? 'text-red-700' : 'text-slate-600'">Reject</span>
                                </label>

                            </div>
                            @error('action') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- WYSIWYG Notes --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">
                                Review Notes <span class="text-red-500">*</span>
                            </label>
                            @error('notes') <p class="text-red-500 text-xs mb-2">{{ $message }}</p> @enderror
                            <div id="quill-editor" class="rounded-2xl overflow-hidden border @error('notes') border-red-400 @else border-slate-200 @enderror" style="min-height:180px"></div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    var quill = new Quill('#quill-editor', {
                                        theme: 'snow',
                                        placeholder: 'Write detailed review notes...',
                                        modules: {
                                            toolbar: [
                                                ['bold', 'italic', 'underline'],
                                                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                                                ['clean']
                                            ]
                                        }
                                    });
                                    @if(old('notes'))
                                    quill.root.innerHTML = {!! json_encode(old('notes')) !!};
                                    @endif
                                });
                            </script>
                        </div>

                        <div class="flex gap-x-3">
                            <button type="submit" x-show="action === ''"
                                    class="flex-1 h-11 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-sm rounded-3xl transition flex items-center justify-center gap-x-2">
                                <i class="fa-solid fa-paper-plane text-xs"></i> Submit Decision
                            </button>
                            <button type="submit" x-show="action === 'needs_revision'"
                                    class="flex-1 h-11 bg-orange-600 hover:bg-orange-700 text-white font-semibold text-sm rounded-3xl transition flex items-center justify-center gap-x-2">
                                <i class="fa-solid fa-exclamation-triangle text-xs"></i> Request Revision
                            </button>
                            <button type="submit" x-show="action === 'approved'"
                                    class="flex-1 h-11 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-3xl transition flex items-center justify-center gap-x-2">
                                <i class="fa-solid fa-check-circle text-xs"></i> Approve Document
                            </button>
                            <button type="submit" x-show="action === 'rejected'"
                                    class="flex-1 h-11 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm rounded-3xl transition flex items-center justify-center gap-x-2">
                                <i class="fa-solid fa-times-circle text-xs"></i> Reject Document
                            </button>
                        </div>
                        <p class="text-[11px] text-slate-400 text-center">Email notification is automatically sent to the user</p>
                    </div>
                </form>
            </div>
            @else
            <div class="bg-slate-50 border border-slate-200 rounded-3xl p-6 text-center">
                <i class="fa-solid fa-circle-check text-3xl {{ $document->status === 'APPROVED' ? 'text-emerald-400' : 'text-slate-300' }} mb-3"></i>
                <p class="font-semibold text-slate-700 text-sm">Document is Final</p>
                <p class="text-xs text-slate-500 mt-1">Status: {{ $document->statusLabel() }}</p>
                @if($document->approved_at)
                    <p class="text-xs text-emerald-600 mt-2">Approved on {{ $document->approved_at->format('d/m/Y H:i') }}</p>
                @endif
            </div>
            @endif

        </div>

        {{-- RIGHT: Review History (moved from left column) --}}
        <div class="lg:col-span-1">
            @if($reviews->isNotEmpty())
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sticky top-24" style="box-shadow:0 1px 3px rgba(15,23,42,0.05)">
                <h4 class="font-semibold text-slate-900 mb-4 flex items-center gap-x-2">
                    <i class="fa-solid fa-clock-rotate-left text-slate-400 text-sm"></i> Review History
                </h4>
                <div class="space-y-3">
                    @foreach($reviews as $review)
                    <div class="border border-slate-100 rounded-2xl p-4">
                        <div class="flex justify-between items-center mb-2">
                            <div class="flex items-center gap-x-2">
                                <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center text-[10px] font-bold text-red-700">
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
                            <div class="text-sm text-slate-600 bg-slate-50 rounded-xl px-3 py-2 mt-2 prose prose-sm max-w-none">{!! $review->notes !!}</div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="bg-slate-50 border border-slate-100 rounded-3xl p-6 text-center text-slate-400 text-sm sticky top-24">
                <i class="fa-solid fa-clock-rotate-left text-2xl text-slate-200 mb-2 block"></i>
                No review history yet.
            </div>
            @endif
        </div>

    </div>

</x-admin-layout>
