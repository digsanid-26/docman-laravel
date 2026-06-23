<!DOCTYPE html>
<html><body style="font-family:sans-serif;max-width:600px;margin:auto;padding:20px;color:#333">
<h2 style="color:#16a34a">✅ Dokumen Disetujui</h2>
<p>Halo <strong>{{ $document->user->name }}</strong>,</p>
<p>Selamat! Dokumen Anda telah disetujui dan tersimpan di arsip digital.</p>
<table style="width:100%;border-collapse:collapse;margin:16px 0">
    <tr><td style="padding:8px;background:#f3f4f6;font-weight:600">Judul</td><td style="padding:8px;border-bottom:1px solid #e5e7eb">{{ $document->title }}</td></tr>
    <tr><td style="padding:8px;background:#f3f4f6;font-weight:600">Jenis</td><td style="padding:8px;border-bottom:1px solid #e5e7eb">{{ $document->document_type }}</td></tr>
    <tr><td style="padding:8px;background:#f3f4f6;font-weight:600">Tanggal Disetujui</td><td style="padding:8px">{{ $document->approved_at?->format('d/m/Y H:i') }}</td></tr>
</table>
@if($document->admin_notes)
<div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:16px;margin:16px 0">
    <p style="font-weight:600;color:#15803d;margin:0 0 8px">Catatan Admin:</p>
    <div style="margin:0;color:#166534">{!! $document->admin_notes !!}</div>
</div>
@endif
<a href="{{ url('/documents/' . $document->id) }}" style="display:inline-block;background:#16a34a;color:#fff;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:600">
    Lihat Detail Dokumen
</a>
<p style="margin-top:24px;font-size:12px;color:#9ca3af">DMS Docman &mdash; {{ config('app.url') }}</p>
</body></html>
