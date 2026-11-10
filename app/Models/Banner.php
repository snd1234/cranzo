<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'desktop_image',
        'mobile_image',
        'button_text',
        'button_url',
        'sort_order',
        'status',
    ];

    public function imageUrl(?string $filename): ?string
    {
        if (! $filename) {
            return null;
        }

        return asset('uploads/banners/'.basename($filename));
    }
}
