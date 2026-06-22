<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DocumentApprovedNotification extends Notification
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
            ->subject('[DMS Docman] Dokumen Disetujui: ' . $this->document->title)
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Dokumen **"' . $this->document->title . '"** telah **disetujui** oleh admin.')
            ->line('**Catatan Admin:**')
            ->line($this->notes)
            ->action('Lihat Dokumen', route('documents.show', $this->document))
            ->line('Terima kasih telah menggunakan DMS Docman.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'document_id' => $this->document->id,
            'title'       => $this->document->title,
            'message'     => 'Dokumen "' . $this->document->title . '" telah disetujui oleh admin.',
            'type'        => 'document_approved',
            'url'         => route('documents.show', $this->document),
        ];
    }
}
