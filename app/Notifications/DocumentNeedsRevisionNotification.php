<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Str;

class DocumentNeedsRevisionNotification extends Notification
{
    use Queueable;

    public function __construct(public Document $document, public string $notes) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('[DMS Docman] Dokumen Perlu Direvisi: ' . $this->document->title)
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Dokumen **"' . $this->document->title . '"** perlu direvisi.')
            ->line('**Catatan Admin:**')
            ->line($this->notes)
            ->action('Lihat Detail Dokumen', route('documents.show', $this->document))
            ->line('Silakan perbaiki dan kirim ulang dokumen Anda.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'document_id' => $this->document->id,
            'title'       => $this->document->title,
            'message'     => 'Dokumen "' . $this->document->title . '" perlu direvisi. Catatan: ' . Str::limit($this->notes, 80),
            'type'        => 'document_needs_revision',
            'url'         => route('documents.show', $this->document),
        ];
    }
}
