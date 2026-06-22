<?php

namespace App\Exports;

use App\Models\Document;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DocumentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function __construct(private ?string $status = null) {}

    public function collection()
    {
        $query = Document::with(['user', 'reviewer'])->latest();

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Judul Dokumen',
            'Jenis Dokumen',
            'Nama Pengirim',
            'Email Pengirim',
            'Tanggal Dokumen',
            'Tanggal Submit',
            'Status',
            'Catatan Admin',
            'Tanggal Disetujui',
            'Path File Approved',
        ];
    }

    public function map($doc): array
    {
        return [
            $doc->id,
            $doc->title,
            $doc->document_type,
            $doc->user->name ?? '-',
            $doc->user->email ?? '-',
            $doc->document_date?->format('d/m/Y'),
            $doc->created_at->format('d/m/Y H:i'),
            $doc->status,
            $doc->admin_notes ?? '-',
            $doc->approved_at?->format('d/m/Y H:i') ?? '-',
            $doc->approved_file_path ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
