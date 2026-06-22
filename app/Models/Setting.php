<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value by key, with optional default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $val = static::where('key', $key)->value('value');
        return $val !== null ? $val : $default;
    }

    /**
     * Set (upsert) a setting value.
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /**
     * Get an email template for a given event type.
     * Returns ['subject' => ..., 'body' => ...] with fallback defaults.
     */
    public static function emailTemplate(string $type): array
    {
        $stored = static::get('email_template_' . $type);

        if ($stored) {
            $decoded = json_decode($stored, true);
            if (is_array($decoded) && isset($decoded['subject'], $decoded['body'])) {
                return $decoded;
            }
        }

        return static::defaultTemplate($type);
    }

    /**
     * Resolve placeholders in a template string.
     * Supported: {name}, {document_title}, {document_type}, {sender_name}, {notes}
     */
    public static function resolvePlaceholders(string $text, array $vars): string
    {
        foreach ($vars as $key => $value) {
            $text = str_replace('{' . $key . '}', $value ?? '', $text);
        }
        return $text;
    }

    /**
     * Hardcoded fallback templates (English) per event type.
     */
    public static function defaultTemplate(string $type): array
    {
        return match ($type) {
            'submitted' => [
                'subject' => '[DMS Docman] New Document: {document_title}',
                'body'    => "A new document has been submitted by **{sender_name}** and is awaiting your review.\n\n**Title:** {document_title}\n**Type:** {document_type}\n\nPlease review it as soon as possible.",
            ],
            'under_review' => [
                'subject' => '[DMS Docman] Your Document Is Under Review',
                'body'    => "Your document **\"{document_title}\"** is currently being reviewed by the admin.\n\nYou will be notified once the review is complete.",
            ],
            'needs_revision' => [
                'subject' => '[DMS Docman] Document Needs Revision: {document_title}',
                'body'    => "Your document **\"{document_title}\"** requires revision.\n\n**Admin Notes:**\n{notes}\n\nPlease make the necessary corrections and resubmit.",
            ],
            'resubmitted' => [
                'subject' => '[DMS Docman] Document Revision Submitted: {document_title}',
                'body'    => "**{sender_name}** has submitted a revision for document **\"{document_title}\"**.\n\nThe document is ready for re-review.\n\nPlease review it as soon as possible.",
            ],
            'rejected' => [
                'subject' => '[DMS Docman] Document Rejected: {document_title}',
                'body'    => "We regret to inform you that your document **\"{document_title}\"** has been **rejected**.\n\n**Reason:**\n{notes}\n\nIf you have questions, please contact the admin.",
            ],
            'approved' => [
                'subject' => '[DMS Docman] Document Approved: {document_title}',
                'body'    => "Your document **\"{document_title}\"** has been **approved** by the admin.\n\n**Admin Notes:**\n{notes}\n\nThank you for using DMS Docman.",
            ],
            default => [
                'subject' => '[DMS Docman] Document Update: {document_title}',
                'body'    => 'There is an update on your document **"{document_title}"**.',
            ],
        };
    }

    /**
     * All configurable template types with their labels.
     */
    public static function templateTypes(): array
    {
        return [
            'submitted'      => 'Document Submitted (to admin)',
            'under_review'   => 'Document Under Review (to user)',
            'needs_revision' => 'Needs Revision (to user)',
            'resubmitted'    => 'Revision Resubmitted (to admin)',
            'rejected'       => 'Document Rejected (to user)',
            'approved'       => 'Document Approved (to user)',
        ];
    }
}
