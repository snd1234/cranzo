<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    protected $table = 'product_details';

    protected $primaryKey = 'product_details_id';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'product_details_id',
        'product_id',
        'color_id',
        'product_quantity',
    ];
}
