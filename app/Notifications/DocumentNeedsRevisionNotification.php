<?php

namespace App\Notifications;

use App\Models\Document;
use App\Models\Setting;
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
        $tpl  = Setting::emailTemplate('needs_revision');
        $vars = [
            'name'           => $notifiable->name,
            'document_title' => $this->document->title,
            'document_type'  => $this->document->document_type ?? '',
            'sender_name'    => $notifiable->name,
            'notes'          => $this->notes,
        ];
        return (new MailMessage)
            ->subject(Setting::resolvePlaceholders($tpl['subject'], $vars))
            ->greeting('Hello, ' . $notifiable->name)
            ->line(Setting::resolvePlaceholders($tpl['body'], $vars))
            ->action('View Document', route('documents.show', $this->document));
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
