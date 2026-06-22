<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total'          => Document::count(),
            'submitted'      => Document::where('status', 'SUBMITTED')->count(),
            'under_review'   => Document::where('status', 'UNDER_REVIEW')->count(),
            'needs_revision' => Document::where('status', 'NEEDS_REVISION')->count(),
            'approved'       => Document::where('status', 'APPROVED')->count(),
            'rejected'       => Document::where('status', 'REJECTED')->count(),
        ];

        $recent = Document::with('user')->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recent'));
    }
}
