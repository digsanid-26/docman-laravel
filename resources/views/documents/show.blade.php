<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-x-3">
            <a href="{{ route('documents.index') }}"
               class="flex items-center gap-x-1.5 text-sm text-slate-500 hover:text-slate-800 transition">
                <i class="fa-solid fa-arrow-left text-xs"></i> My Documents
            </a>
            <span class="text-slate-300">/</span>
            <h2 class="font-semibold text-slate-900 truncate max-w-xs text-sm">{{ Str::limit($document->title, 40) }}</h2>
        </div>
    </x-slot>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 space-y-5">

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-2xl flex items-center gap-x-2">
                    <i class="fa-solid fa-check-circle text-emerald-500"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-2xl flex items-center gap-x-2">
                    <i class="fa-solid fa-circle-exclamation text-red-500"></i> {{ session('error') }}
                </div>
            @endif

            {{-- Status Banner --}}
            @if($document->status === 'NEEDS_REVISION')
                <div class="bg-orange-50 border border-orange-200 rounded-3xl p-5 flex items-start gap-x-4">
                    <div class="w-10 h-10 bg-orange-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-exclamation-triangle text-orange-500"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-orange-800 mb-1">Document Needs Revision</p>
                        <div class="text-orange-700 text-sm leading-relaxed prose prose-sm max-w-none">{!! $document->admin_notes !!}</div>
                        <p class="text-xs text-orange-500 mt-2">Please make the necessary corrections and resubmit using the form below.</p>
                    </div>
                </div>
            @elseif($document->status === 'APPROVED')
                <div class="bg-emerald-50 border border-emerald-200 rounded-3xl p-5 flex items-center gap-x-4">
                    <div class="w-10 h-10 bg-emerald-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-check-circle text-emerald-500 text-lg"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-emerald-800">Document Approved</p>
                        <p class="text-emerald-600 text-sm">Approved on {{ $document->approved_at?->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            @elseif($document->status === 'REJECTED')
                <div class="bg-red-50 border border-red-200 rounded-3xl p-5 flex items-start gap-x-4">
                    <div class="w-10 h-10 bg-red-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-times-circle text-red-500 text-lg"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-red-800 mb-1">Document Rejected</p>
                        <div class="text-red-700 text-sm leading-relaxed prose prose-sm max-w-none">{!! $document->admin_notes !!}</div>
                    </div>
                </div>
            @elseif($document->status === 'UNDER_REVIEW')
                <div class="bg-amber-50 border border-amber-200 rounded-3xl p-5 flex items-center gap-x-4">
                    <div class="w-10 h-10 bg-amber-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-eye text-amber-500"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-amber-800">Under Review</p>
                        <p class="text-amber-600 text-sm">An admin is currently reviewing your document.</p>
                    </div>
                </div>
            @endif

            {{-- Document Card --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 space-y-5" style="box-shadow:0 1px 3px rgba(15,23,42,0.05)">
                <div class="flex justify-between items-start gap-x-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">{{ $document->title }}</h3>
                        <p class="text-sm text-slate-500 mt-0.5">{{ $document->document_type }}</p>
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
                        <p class="text-xs text-slate-400 font-medium uppercase tracking-wide mb-1">Document Date</p>
                        <p class="font-medium text-slate-800">{{ $document->document_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase tracking-wide mb-1">Submitted</p>
                        <p class="font-medium text-slate-800">{{ $document->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <div>
                    <p class="text-xs text-slate-400 font-medium uppercase tracking-wide mb-2">Description</p>
                    <p class="text-slate-700 bg-slate-50 rounded-2xl px-4 py-3 text-sm leading-relaxed">{{ $document->description }}</p>
                </div>
            </div>

            {{-- Resubmit Form --}}
            @if($document->status === 'NEEDS_REVISION')
            <div class="bg-white border-2 border-orange-200 rounded-3xl p-6" style="box-shadow:0 1px 3px rgba(15,23,42,0.05)">
                <h4 class="font-semibold text-slate-900 mb-1 flex items-center gap-x-2">
                    <i class="fa-solid fa-upload text-orange-500 text-sm"></i> Resubmit Revision
                </h4>
                <p class="text-xs text-slate-500 mb-5">Upload your revised file and add notes describing the changes you made.</p>

                <form method="POST" action="{{ route('documents.resubmit', $document) }}"
                      enctype="multipart/form-data" x-data="{ fileName: '' }">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">
                                Revision File <span class="text-red-500">*</span>
                            </label>
                            <label class="flex items-center gap-x-3 border-2 border-dashed border-slate-200 hover:border-orange-400 rounded-2xl px-4 py-4 cursor-pointer transition"
                                   :class="fileName ? 'border-orange-400 bg-orange-50' : ''">
                                <i class="fa-solid fa-file-arrow-up text-slate-400 text-xl flex-shrink-0"
                                   :class="fileName ? 'text-orange-500' : ''"></i>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-slate-700" x-text="fileName || 'Choose file...'"></p>
                                    <p class="text-xs text-slate-400">PDF, DOC, DOCX, JPG, PNG — max. 10 MB</p>
                                </div>
                                <input type="file" name="file" accept=".pdf,.doc,.docx,.jpg,.png" class="hidden" required
                                       x-on:change="fileName = $event.target.files[0]?.name ?? ''">
                            </label>
                            @error('file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">
                                Change Notes <span class="text-slate-400 font-normal normal-case">(optional)</span>
                            </label>
                            <textarea name="revision_notes" rows="3"
                                      class="w-full border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-1 focus:ring-orange-400 focus:border-orange-400 placeholder:text-slate-400 resize-none"
                                      placeholder="Describe the changes you made...">{{ old('revision_notes') }}</textarea>
                            @error('revision_notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit"
                                class="w-full h-11 bg-orange-500 hover:bg-orange-600 text-white font-semibold text-sm rounded-3xl transition flex items-center justify-center gap-x-2">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                            Submit Revision
                        </button>
                    </div>
                </form>
            </div>
            @endif

            {{-- Review History --}}
            @if($reviews->isNotEmpty())
            <div class="bg-white border border-slate-100 rounded-3xl p-6" style="box-shadow:0 1px 3px rgba(15,23,42,0.05)">
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
            @endif

        </div>
    </div>
</x-app-layout>
