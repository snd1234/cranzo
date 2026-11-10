<?php

namespace App\Models;

use App\Support\CmsPages;
use Illuminate\Database\Eloquent\Model;

class CmsPage extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'canonical_url',
        'status',
        'show_in_footer',
        'footer_group',
        'footer_order',
    ];

    public function publicUrl(): string
    {
        return $this->canonical_url ?: CmsPages::url($this->slug);
    }

    public function imageUrl(): ?string
    {
        return $this->image ? asset('uploads/cms/'.basename($this->image)) : null;
    }
}
