<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DocumentSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(public Document $document) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('[DMS Docman] Dokumen Baru: ' . $this->document->title)
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Dokumen baru telah dikirim oleh **' . $this->document->user->name . '** dan menunggu review Anda.')
            ->line('**Judul:** ' . $this->document->title)
            ->line('**Jenis:** ' . $this->document->document_type)
            ->action('Review Dokumen', route('admin.documents.show', $this->document))
            ->line('Harap segera direview.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'document_id' => $this->document->id,
            'title'       => $this->document->title,
            'user_name'   => $this->document->user->name,
            'message'     => 'Dokumen baru dari ' . $this->document->user->name . ': "' . $this->document->title . '"',
            'type'        => 'document_submitted',
            'url'         => route('admin.documents.show', $this->document),
        ];
    }
}
