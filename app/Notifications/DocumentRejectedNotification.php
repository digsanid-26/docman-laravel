<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Str;

class DocumentRejectedNotification extends Notification
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
            ->subject('[DMS Docman] Dokumen Ditolak: ' . $this->document->title)
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Mohon maaf, dokumen **"' . $this->document->title . '"** telah **ditolak** oleh admin.')
            ->line('**Alasan Penolakan:**')
            ->line($this->notes)
            ->action('Lihat Detail Dokumen', route('documents.show', $this->document))
            ->line('Jika ada pertanyaan, silakan hubungi admin.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'document_id' => $this->document->id,
            'title'       => $this->document->title,
            'message'     => 'Dokumen "' . $this->document->title . '" ditolak. Alasan: ' . Str::limit($this->notes, 80),
            'type'        => 'document_rejected',
            'url'         => route('documents.show', $this->document),
        ];
    }
}
