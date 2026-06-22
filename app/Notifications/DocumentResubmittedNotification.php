<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DocumentResubmittedNotification extends Notification
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
            ->subject('[DMS Docman] Revisi Dokumen: ' . $this->document->title)
            ->greeting('Halo, ' . $notifiable->name)
            ->line('**' . $this->document->user->name . '** telah mengirimkan revisi untuk dokumen **"' . $this->document->title . '"**.')
            ->line('Dokumen siap untuk direview kembali.')
            ->action('Review Revisi', route('admin.documents.show', $this->document))
            ->line('Harap segera direview.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'document_id' => $this->document->id,
            'title'       => $this->document->title,
            'user_name'   => $this->document->user->name,
            'message'     => $this->document->user->name . ' mengirim revisi untuk: "' . $this->document->title . '"',
            'type'        => 'document_resubmitted',
            'url'         => route('admin.documents.show', $this->document),
        ];
    }
}
