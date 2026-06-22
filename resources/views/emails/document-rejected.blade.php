<!DOCTYPE html>
<html><body style="font-family:sans-serif;max-width:600px;margin:auto;padding:20px;color:#333">
<h2 style="color:#4b5563">❌ Dokumen Ditolak</h2>
<p>Halo <strong>{{ $document->user->name }}</strong>,</p>
<p>Dokumen Anda tidak dapat disetujui.</p>
<table style="width:100%;border-collapse:collapse;margin:16px 0">
    <tr><td style="padding:8px;background:#f3f4f6;font-weight:600">Judul</td><td style="padding:8px;border-bottom:1px solid #e5e7eb">{{ $document->title }}</td></tr>
    <tr><td style="padding:8px;background:#f3f4f6;font-weight:600">Jenis</td><td style="padding:8px">{{ $document->document_type }}</td></tr>
</table>
<div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:16px;margin:16px 0">
    <p style="font-weight:600;color:#374151;margin:0 0 8px">Alasan Penolakan:</p>
    <p style="margin:0;color:#4b5563">{{ $document->admin_notes }}</p>
</div>
<p>Jika ada pertanyaan, silakan hubungi tim admin.</p>
<a href="{{ url('/documents/' . $document->id) }}" style="display:inline-block;background:#4b5563;color:#fff;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:600">
    Lihat Detail Dokumen
</a>
<p style="margin-top:24px;font-size:12px;color:#9ca3af">DMS Docman &mdash; {{ config('app.url') }}</p>
</body></html>
