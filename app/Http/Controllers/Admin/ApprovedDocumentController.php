<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;

class ApprovedDocumentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Document::where('status', 'APPROVED')
            ->with(['user', 'reviewer'])
            ->withCount(['reviews as revision_count' => function ($q) {
                $q->where('action', 'needs_revision');
            }])
            ->latest('approved_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $documents = $query->paginate(20)->withQueryString();

        return view('admin.documents.approved', compact('documents', 'search'));
    }
}
