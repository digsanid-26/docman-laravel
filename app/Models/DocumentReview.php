<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentReview extends Model
{
    public $timestamps = false;

    protected $fillable = ['document_id', 'admin_id', 'action', 'notes', 'created_at'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function actionLabel(): string
    {
        return match ($this->action) {
            'needs_revision' => 'Needs Revision',
            'approved'       => 'Approved',
            'rejected'       => 'Rejected',
            default          => $this->action,
        };
    }

    public function actionBadge(): string
    {
        return match ($this->action) {
            'needs_revision' => 'bg-red-100 text-red-700',
            'approved'       => 'bg-green-100 text-green-700',
            'rejected'       => 'bg-gray-100 text-gray-700',
            default          => 'bg-gray-100 text-gray-600',
        };
    }
}
