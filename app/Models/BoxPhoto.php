<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoxPhoto extends Model
{
    protected $fillable = [
        'storage_box_id',
        'photo_path',
    ];

    public function storageBox()
    {
        return $this->belongsTo(StorageBox::class);
    }
}
