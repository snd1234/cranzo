<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Address
 *
 * @property int $id
 * @property int $user_id
 * @property string $address
 * @property string $city
 * @property int $state_id
 * @property int $pincode
 */
class Address extends Model
{

    /**
     * Table associated with the model.
     *
     * @var string
     */
    protected $table = 'address';

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
        'address',
        'city',
        'state_id',
        'pincode'
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

    public function State()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }
}