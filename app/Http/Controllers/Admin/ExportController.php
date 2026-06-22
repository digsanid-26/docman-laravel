<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DocumentsExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function export(Request $request)
    {
        $status   = $request->query('status');
        $filename = 'dokumen-export-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new DocumentsExport($status), $filename);
    }
}
