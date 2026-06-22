<?php

namespace App\Notifications;

use App\Models\Document;
use App\Models\Setting;
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
        $tpl  = Setting::emailTemplate('submitted');
        $vars = [
            'name'           => $notifiable->name,
            'document_title' => $this->document->title,
            'document_type'  => $this->document->document_type,
            'sender_name'    => $this->document->user->name,
            'notes'          => '',
        ];
        return (new MailMessage)
            ->subject(Setting::resolvePlaceholders($tpl['subject'], $vars))
            ->greeting('Hello, ' . $notifiable->name)
            ->line(Setting::resolvePlaceholders($tpl['body'], $vars))
            ->action('Review Document', route('admin.documents.show', $this->document));
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
