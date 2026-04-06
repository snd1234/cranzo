<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderDetail
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property int $quantity
 * @property float $price
 * @property float $total
 */
class OrderDetail extends Model
{

    /**
     * Table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_details';

    /**
     * Primary key.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Mass assignable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'order_quantity',
        'amount_per_item'
    ];

    /**
     * Attribute casting.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'order_id' => 'integer',
        'product_id' => 'integer',
        'order_quantity' => 'integer',
        'amount_per_item' => 'float'
    ];

    public function Order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}