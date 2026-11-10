<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $table = 'product_images';

    protected $primaryKey = 'image_id';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'image_id',
        'product_details_id',
        'product_id',
        'thumbnail',
        'image',
        'created_date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
