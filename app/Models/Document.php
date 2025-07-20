<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use SoftDeletes;

    protected $table = 'documents';

    protected $fillable = [
        'name',
        'description',
        'status',
        'user_id',
        'current_version_id',
        'file_path',
    ];

    protected static function booted()
    {
        static::forceDeleted(function ($document) {
            // Hapus file dari storage
            if ($document->file_path && Storage::exists('storage/' . $document->file_path)) {
                Storage::delete('storage/' . $document->file_path);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function versions()
    {
        return $this->hasMany(DocumentVersion::class);
    }

    public function currentVersion()
    {
        return $this->belongsTo(DocumentVersion::class, 'current_version_id');
    }

    public function logs()
    {
        return $this->hasMany(DocumentLog::class);
    }
}
