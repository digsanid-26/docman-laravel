<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Mail\DocumentSubmitted;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = auth()->user()->documents()->latest()->paginate(10);
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

        $path = $request->file('file')->store('documents/' . auth()->id(), 'local');

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
            Mail::to($admin->email)->queue(new DocumentSubmitted($document, $admin));
        }

        return redirect()->route('documents.index')
            ->with('success', 'Dokumen berhasil dikirim! Admin akan segera mereview dokumen Anda.');
    }

    public function show(Document $document)
    {
        if ($document->user_id !== auth()->id()) {
            abort(403);
        }

        $reviews = $document->reviews()->with('admin')->get();
        return view('documents.show', compact('document', 'reviews'));
    }
}
