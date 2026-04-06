<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\State
 *
 * @property int $id
 * @property string $state_name
 */
class State extends Model
{

    protected $table = 'state';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'state_name'
    ];
    protected $casts = [
        'id' => 'integer',
    ];

}