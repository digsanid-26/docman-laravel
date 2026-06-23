<!DOCTYPE html>
<html><body style="font-family:sans-serif;max-width:600px;margin:auto;padding:20px;color:#333">
<h2 style="color:#dc2626">⚠️ Dokumen Perlu Revisi</h2>
<p>Halo <strong>{{ $document->user->name }}</strong>,</p>
<p>Dokumen Anda memerlukan revisi sebelum dapat disetujui.</p>
<table style="width:100%;border-collapse:collapse;margin:16px 0">
    <tr><td style="padding:8px;background:#f3f4f6;font-weight:600">Judul</td><td style="padding:8px;border-bottom:1px solid #e5e7eb">{{ $document->title }}</td></tr>
    <tr><td style="padding:8px;background:#f3f4f6;font-weight:600">Jenis</td><td style="padding:8px;border-bottom:1px solid #e5e7eb">{{ $document->document_type }}</td></tr>
</table>
<div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:16px;margin:16px 0">
    <p style="font-weight:600;color:#b91c1c;margin:0 0 8px">Catatan dari Admin:</p>
    <div style="margin:0;color:#7f1d1d">{!! $document->admin_notes !!}</div>
</div>
<a href="{{ url('/documents/' . $document->id) }}" style="display:inline-block;background:#dc2626;color:#fff;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:600">
    Lihat Detail Dokumen
</a>
<p style="margin-top:24px;font-size:12px;color:#9ca3af">DMS Docman &mdash; {{ config('app.url') }}</p>
</body></html>
