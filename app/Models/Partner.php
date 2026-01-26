<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Partner extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'short_description',
        'website_url',
        'sort_order',
        'status',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    
}
