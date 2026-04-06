<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Orders
 *
 * @property int $id
 * @property int $user_id
 * @property string $order_number
 * @property string $order_status
 * @property \Illuminate\Support\Carbon|null $booking_date
 */
class Orders extends Model
{

    /**
     * Table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders';

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
        'user_id',
        'order_number',
        'order_status',
        'booking_date',
        'user_id'
    ];

    /**
     * Attribute casting.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer'
    ];

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function OrderDetail()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    public function Address()
    {
        return $this->belongsTo(Address::class, 'address_id', 'id');
    }
}