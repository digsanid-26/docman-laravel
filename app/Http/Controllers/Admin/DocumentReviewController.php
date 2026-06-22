<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\DocumentApproved;
use App\Mail\DocumentNeedsRevision;
use App\Mail\DocumentRejected;
use App\Models\Document;
use App\Models\DocumentReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentReviewController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $query  = Document::with('user')->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $documents = $query->paginate(15)->withQueryString();

        return view('admin.documents.index', compact('documents', 'status'));
    }

    public function show(Document $document)
    {
        if ($document->status === 'SUBMITTED') {
            $document->update(['status' => 'UNDER_REVIEW']);
        }

        $reviews = $document->reviews()->with('admin')->get();

        return view('admin.documents.show', compact('document', 'reviews'));
    }

    public function review(Request $request, Document $document)
    {
        if ($document->isFinal()) {
            return back()->with('error', 'Dokumen ini sudah final dan tidak bisa diubah.');
        }

        $validated = $request->validate([
            'action' => 'required|in:needs_revision,approved,rejected',
            'notes'  => 'required|string|max:2000',
        ], [
            'action.required' => 'Pilih aksi review.',
            'notes.required'  => 'Catatan wajib diisi.',
        ]);

        $statusMap = [
            'needs_revision' => 'NEEDS_REVISION',
            'approved'       => 'APPROVED',
            'rejected'       => 'REJECTED',
        ];

        $newStatus = $statusMap[$validated['action']];

        $approvedPath = null;
        if ($validated['action'] === 'approved') {
            $approvedPath = $this->moveToApproved($document);
        }

        $document->update([
            'status'             => $newStatus,
            'reviewed_by'        => auth()->id(),
            'admin_notes'        => $validated['notes'],
            'approved_at'        => $validated['action'] === 'approved' ? now() : null,
            'approved_file_path' => $approvedPath,
        ]);

        DocumentReview::create([
            'document_id' => $document->id,
            'admin_id'    => auth()->id(),
            'action'      => $validated['action'],
            'notes'       => $validated['notes'],
            'created_at'  => now(),
        ]);

        match ($validated['action']) {
            'needs_revision' => Mail::to($document->user->email)->queue(new DocumentNeedsRevision($document)),
            'approved'       => Mail::to($document->user->email)->queue(new DocumentApproved($document)),
            'rejected'       => Mail::to($document->user->email)->queue(new DocumentRejected($document)),
        };

        $label = match ($validated['action']) {
            'needs_revision' => 'Dokumen dikembalikan untuk revisi.',
            'approved'       => 'Dokumen berhasil disetujui.',
            'rejected'       => 'Dokumen ditolak.',
        };

        return redirect()->route('admin.documents.index')
            ->with('success', $label . ' Email notifikasi telah dikirim ke ' . $document->user->name . '.');
    }

    public function download(Document $document)
    {
        $path = $document->status === 'APPROVED' && $document->approved_file_path
            ? $document->approved_file_path
            : $document->file_path;

        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('local')->download($path);
    }

    private function moveToApproved(Document $document): string
    {
        $year  = now()->year;
        $month = now()->format('m');
        $ext   = pathinfo($document->file_path, PATHINFO_EXTENSION);
        $slug  = Str::slug($document->title);
        $newName = now()->format('Ymd') . '_' . $document->id . '_' . $slug . '.' . $ext;
        $newPath = "approved/{$year}/{$month}/{$newName}";

        Storage::disk('local')->copy($document->file_path, $newPath);

        return $newPath;
    }
}
