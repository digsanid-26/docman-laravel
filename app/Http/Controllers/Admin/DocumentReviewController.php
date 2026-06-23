<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentReview;
use App\Notifications\DocumentApprovedNotification;
use App\Notifications\DocumentNeedsRevisionNotification;
use App\Notifications\DocumentRejectedNotification;
use App\Notifications\DocumentUnderReviewNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentReviewController extends Controller
{
    public function index(Request $request)
    {
        $status  = $request->query('status');
        $perPage = in_array((int) $request->query('per_page'), [5, 10, 20, 50])
                   ? (int) $request->query('per_page')
                   : 15;
        $query   = Document::with('user')->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $documents = $query->paginate($perPage)->withQueryString();

        return view('admin.documents.index', compact('documents', 'status', 'perPage'));
    }

    public function destroy(Document $document)
    {
        if ($document->file_path) {
            Storage::disk('local')->delete($document->file_path);
        }
        if ($document->approved_file_path) {
            Storage::disk('local')->delete($document->approved_file_path);
        }
        $document->delete();

        return redirect()->route('admin.documents.index')
            ->with('success', 'Document deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = array_filter((array) $request->input('ids', []));
        if (empty($ids)) {
            return back()->with('error', 'No documents selected.');
        }

        $documents = Document::whereIn('id', $ids)->get();
        foreach ($documents as $doc) {
            if ($doc->file_path)          Storage::disk('local')->delete($doc->file_path);
            if ($doc->approved_file_path) Storage::disk('local')->delete($doc->approved_file_path);
            $doc->delete();
        }

        return redirect()->route('admin.documents.index')
            ->with('success', count($documents) . ' document(s) deleted.');
    }

    public function show(Document $document)
    {
        if ($document->status === 'SUBMITTED') {
            $document->update(['status' => 'UNDER_REVIEW']);
            try {
                $document->user->notify(new DocumentUnderReviewNotification($document));
            } catch (\Throwable $e) {
                Log::error('DocumentUnderReview notification failed: ' . $e->getMessage());
            }
        }

        $reviews = $document->reviews()->with('admin')->get();

        return view('admin.documents.show', compact('document', 'reviews'));
    }

    public function review(Request $request, Document $document)
    {
        if ($document->isFinal()) {
            return back()->with('error', 'This document is already final and cannot be changed.');
        }

        $validated = $request->validate([
            'action' => 'required|in:needs_revision,approved,rejected',
            'notes'  => 'required|string|max:10000',
        ], [
            'action.required' => 'Please select a review action.',
            'notes.required'  => 'Review notes are required.',
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

        try {
            match ($validated['action']) {
                'needs_revision' => $document->user->notify(new DocumentNeedsRevisionNotification($document, $validated['notes'])),
                'approved'       => $document->user->notify(new DocumentApprovedNotification($document, $validated['notes'])),
                'rejected'       => $document->user->notify(new DocumentRejectedNotification($document, $validated['notes'])),
            };
        } catch (\Throwable $e) {
            Log::error('Document review notification failed: ' . $e->getMessage());
        }

        $label = match ($validated['action']) {
            'needs_revision' => 'Document returned for revision.',
            'approved'       => 'Document approved successfully.',
            'rejected'       => 'Document rejected.',
        };

        return redirect()->route('admin.documents.index')
            ->with('success', $label . ' Notification email sent to ' . $document->user->name . '.');
    }

    public function download(Document $document)
    {
        $path = $document->status === 'APPROVED' && $document->approved_file_path
            ? $document->approved_file_path
            : $document->file_path;

        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'File not found.');
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
        Storage::disk('local')->delete($document->file_path);

        return $newPath;
    }
}
