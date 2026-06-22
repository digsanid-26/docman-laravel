<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DocumentUnderReviewNotification extends Notification
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
            ->subject('[DMS Docman] Dokumen Anda Sedang Direview')
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Dokumen **"' . $this->document->title . '"** Anda sedang direview oleh admin.')
            ->line('Anda akan mendapat notifikasi segera setelah proses review selesai.')
            ->action('Pantau Status Dokumen', route('documents.show', $this->document));
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'document_id' => $this->document->id,
            'title'       => $this->document->title,
            'message'     => 'Dokumen "' . $this->document->title . '" sedang direview oleh admin.',
            'type'        => 'document_under_review',
            'url'         => route('documents.show', $this->document),
        ];
    }
}
