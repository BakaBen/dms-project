<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';

    protected $fillable = [
        'name',
        'description',
        'status',
        'user_id',
        'file_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
