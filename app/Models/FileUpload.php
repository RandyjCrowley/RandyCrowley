<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FileUpload extends Model
{
    protected $fillable = [
        'user_id',
        'filename',
        'original_filename',
        'mime_type',
        'file_size',
        'path',
        'status',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
