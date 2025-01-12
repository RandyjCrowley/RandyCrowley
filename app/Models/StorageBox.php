<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class StorageBox extends Model
{
    protected $fillable = [
        'box_name',
        'description',
        'user_id',
        'slug', // This will be used in the URL
    ];

    public function getRouteKeyName() : string
    {
        return 'slug';
    }

    protected static function boot() : void
    {
        parent::boot();

        // Generate unique slug for URL before creating
        static::creating(function ($storageBox) {
            $storageBox->slug = Str::uuid();
        });
    }

    // Generate the URL that will be encoded in the QR code
    public function getQrUrlAttribute() : string
    {
        return url("/boxes/{$this->slug}");
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function photos() : HasMany
    {
        return $this->hasMany(BoxPhoto::class);
    }
}
