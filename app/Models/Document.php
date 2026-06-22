<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'slug', 'document_type', 'document_date',
        'description', 'file_path', 'approved_file_path',
        'status', 'reviewed_by', 'admin_notes', 'approved_at',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getRouteKey(): mixed
    {
        return $this->slug ?? $this->id;
    }

    public function resolveRouteBinding($value, $field = null): ?static
    {
        return $this->where('slug', $value)->orWhere('id', $value)->first();
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Document $document) {
            if (empty($document->slug)) {
                $document->slug = static::generateUniqueSlug($document->title);
            }
        });
    }

    public static function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        if (empty($slug)) {
            $slug = 'document';
        }
        $original = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }

    protected $casts = [
        'document_date' => 'date',
        'approved_at'   => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function reviews()
    {
        return $this->hasMany(DocumentReview::class)->latest('created_at');
    }

    public function statusBadge(): string
    {
        return match ($this->status) {
            'SUBMITTED'      => 'bg-blue-100 text-blue-800',
            'UNDER_REVIEW'   => 'bg-yellow-100 text-yellow-800',
            'NEEDS_REVISION' => 'bg-red-100 text-red-800',
            'APPROVED'       => 'bg-green-100 text-green-800',
            'REJECTED'       => 'bg-gray-100 text-gray-800',
            default          => 'bg-gray-100 text-gray-600',
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'SUBMITTED'      => 'Dikirim',
            'UNDER_REVIEW'   => 'Sedang Direview',
            'NEEDS_REVISION' => 'Perlu Revisi',
            'APPROVED'       => 'Disetujui',
            'REJECTED'       => 'Ditolak',
            default          => $this->status,
        };
    }

    public function isFinal(): bool
    {
        return in_array($this->status, ['APPROVED', 'REJECTED']);
    }
}
