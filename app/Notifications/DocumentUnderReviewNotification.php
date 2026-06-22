<?php

namespace App\Notifications;

use App\Models\Document;
use App\Models\Setting;
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
        $tpl  = Setting::emailTemplate('under_review');
        $vars = [
            'name'           => $notifiable->name,
            'document_title' => $this->document->title,
            'document_type'  => $this->document->document_type ?? '',
            'sender_name'    => $notifiable->name,
            'notes'          => '',
        ];
        return (new MailMessage)
            ->subject(Setting::resolvePlaceholders($tpl['subject'], $vars))
            ->greeting('Hello, ' . $notifiable->name)
            ->line(Setting::resolvePlaceholders($tpl['body'], $vars))
            ->action('Track Document Status', route('documents.show', $this->document));
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
