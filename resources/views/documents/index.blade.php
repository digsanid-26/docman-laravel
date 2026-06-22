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

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                @if($documents->isEmpty())
                    <div class="text-center py-16 text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="font-medium">No documents yet</p>
                        <p class="text-sm mt-1">Start by submitting your first document.</p>
                        <a href="{{ route('documents.create') }}" class="mt-4 inline-block text-red-600 hover:underline text-sm">Submit Document</a>
                    </div>
                @else
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left">#</th>
                                <th class="px-6 py-3 text-left">Title</th>
                                <th class="px-6 py-3 text-left">Type</th>
                                <th class="px-6 py-3 text-left">Document Date</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <th class="px-6 py-3 text-left">Submitted</th>
                                <th class="px-6 py-3 text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($documents as $i => $doc)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-gray-400">{{ $documents->firstItem() + $i }}</td>
                                <td class="px-6 py-4 font-medium text-gray-800">{{ $doc->title }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $doc->document_type }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $doc->document_date->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $doc->statusBadge() }}">
                                        {{ $doc->statusLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $doc->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('documents.show', $doc) }}" class="text-red-600 hover:underline text-xs font-medium">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $documents->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
