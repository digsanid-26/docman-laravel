<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\User;
use App\Notifications\DocumentResubmittedNotification;
use App\Notifications\DocumentSubmittedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = auth()->user()->documents()->latest()->paginate(10)->withQueryString();
        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        return view('documents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'document_type' => 'required|string|max:100',
            'document_date' => 'required|date',
            'description'   => 'required|string|max:1000',
            'file'          => 'required|file|mimes:pdf,doc,docx,jpg,png|max:10240',
        ], [
            'title.required'         => 'Judul dokumen wajib diisi.',
            'document_type.required' => 'Jenis dokumen wajib dipilih.',
            'document_date.required' => 'Tanggal dokumen wajib diisi.',
            'description.required'   => 'Deskripsi wajib diisi.',
            'file.required'          => 'File dokumen wajib diunggah.',
            'file.mimes'             => 'Format file harus PDF, DOC, DOCX, JPG, atau PNG.',
            'file.max'               => 'Ukuran file maksimal 10 MB.',
        ]);

        $path = $request->file('file')->store('documents/temp/' . auth()->id(), 'local');

        $document = Document::create([
            'user_id'       => auth()->id(),
            'title'         => $validated['title'],
            'document_type' => $validated['document_type'],
            'document_date' => $validated['document_date'],
            'description'   => $validated['description'],
            'file_path'     => $path,
            'status'        => 'SUBMITTED',
        ]);

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            try {
                $admin->notify(new DocumentSubmittedNotification($document));
            } catch (\Throwable $e) {
                Log::error('DocumentSubmitted notification failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('documents.index')
            ->with('success', 'Document submitted successfully! An admin will review it shortly.');
    }

    public function show(Document $document)
    {
        if ($document->user_id !== auth()->id()) {
            abort(403);
        }

        $reviews = $document->reviews()->with('admin')->get();
        return view('documents.show', compact('document', 'reviews'));
    }

    public function resubmit(Request $request, Document $document)
    {
        if ($document->user_id !== auth()->id()) {
            abort(403);
        }

        if ($document->status !== 'NEEDS_REVISION') {
            return back()->with('error', 'This document is not pending revision.');
        }

        $validated = $request->validate([
            'file'            => 'required|file|mimes:pdf,doc,docx,jpg,png|max:10240',
            'revision_notes'  => 'nullable|string|max:500',
        ], [
            'file.required' => 'File revisi wajib diunggah.',
            'file.mimes'    => 'Format file harus PDF, DOC, DOCX, JPG, atau PNG.',
            'file.max'      => 'Ukuran file maksimal 10 MB.',
        ]);

        Storage::disk('local')->delete($document->file_path);

        $path = $request->file('file')->store('documents/temp/' . auth()->id(), 'local');

        $document->update([
            'file_path'   => $path,
            'status'      => 'SUBMITTED',
            'admin_notes' => $validated['revision_notes'] ? '[Catatan revisi] ' . $validated['revision_notes'] : $document->admin_notes,
            'reviewed_by' => null,
            'approved_at' => null,
        ]);

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            try {
                $admin->notify(new DocumentResubmittedNotification($document));
            } catch (\Throwable $e) {
                Log::error('DocumentResubmitted notification failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('documents.show', $document)
            ->with('success', 'Revision submitted! The admin will re-review your document.');
    }
}
