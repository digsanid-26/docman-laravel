<!DOCTYPE html>
<html><body style="font-family:sans-serif;max-width:600px;margin:auto;padding:20px;color:#333">
<h2 style="color:#2563eb">📄 Dokumen Baru Masuk</h2>
<p>Halo <strong>{{ $admin->name }}</strong>,</p>
<p>Ada dokumen baru yang perlu Anda review:</p>
<table style="width:100%;border-collapse:collapse;margin:16px 0">
    <tr><td style="padding:8px;background:#f3f4f6;font-weight:600">Judul</td><td style="padding:8px;border-bottom:1px solid #e5e7eb">{{ $document->title }}</td></tr>
    <tr><td style="padding:8px;background:#f3f4f6;font-weight:600">Jenis</td><td style="padding:8px;border-bottom:1px solid #e5e7eb">{{ $document->document_type }}</td></tr>
    <tr><td style="padding:8px;background:#f3f4f6;font-weight:600">Pengirim</td><td style="padding:8px;border-bottom:1px solid #e5e7eb">{{ $document->user->name }} ({{ $document->user->email }})</td></tr>
    <tr><td style="padding:8px;background:#f3f4f6;font-weight:600">Dikirim</td><td style="padding:8px">{{ $document->created_at->format('d/m/Y H:i') }}</td></tr>
</table>
<a href="{{ url('/admin/documents/' . $document->id) }}" style="display:inline-block;background:#2563eb;color:#fff;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:600">
    Buka & Review Dokumen
</a>
<p style="margin-top:24px;font-size:12px;color:#9ca3af">DMS Docman &mdash; {{ config('app.url') }}</p>
</body></html>
